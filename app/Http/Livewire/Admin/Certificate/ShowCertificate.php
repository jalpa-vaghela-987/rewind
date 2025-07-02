<?php

namespace App\Http\Livewire\Admin\Certificate;

use App\Models\Certificate;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\SendMessageNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Livewire\Component;
use Livewire\WithFileUploads;

class ShowCertificate extends Component
{
    use WithFileUploads;

    public $certificate;
    public $file_path, $description, $name,$lattitude,$longitude,$verify_by,$registry_id;
    public $currentImageIndex = 0;
    public $uploadedFiles = [];
    public $total_files = 0;

    public $price, $icon, $certificateId, $priceDifference, $differenceType;

    public array $dataset = [];
    public array $labels = [];
    public $maxDays = 14;
    public $maxValue = 100;
    public $stepSize = 10;

    public $user;

    public $uploadedAllPath = [];

    public $acceptedMimes = ['png', 'gif', 'bmp', 'svg',
        'jpg', 'jpeg'];

    protected $listeners = ['changeName' => 'changeNameValue', 'changeDescription' => 'changeDescriptionValue', 'certificate-selected' => 'certificateSelected'];

    public function mount($id)
    {
        $this->certificate = Certificate::find($id);
        $this->uploadedFiles = $this->certificate->files()->pluck('file_path')->toArray();
        $this->name = $this->certificate->name;
        $this->lattitude = $this->certificate->lattitude;
        $this->longitude = $this->certificate->longitude;
        $this->verify_by = $this->certificate->verify_by;
        $this->registry_id = $this->certificate->registry_id;
        //$this->file_path = $this->certificate->file_path;
        $this->description = $this->certificate->description;

        $soldCertificate = Subscription::select('*')->with('certificate')->where('certificate_id', $id)->orderBy('id', 'desc')->first();

        if ( $soldCertificate ) {
            $this->price = $soldCertificate->price;
            $this->name = $soldCertificate->certificate->project_type->type;
            $this->certificateId = $soldCertificate->certificate->id;
            $this->icon = $soldCertificate->certificate->project_type->image_icon;

            $difference = ($soldCertificate->price * 100) / $soldCertificate->certificate->price;
            $difference = abs(100 - $difference);
            $this->priceDifference = $difference;
            $this->differenceType = $soldCertificate->price > $soldCertificate->certificate->price ? 'inc' : 'dec';
        } else {
            $this->price = $this->certificate->price;
        }

        $this->labels = $this->getLabels($this->maxDays);
        $this->dataset = $this->getRandomData($this->maxDays);
    }

    public function certificateSelected($prop)
    {
        $this->maxDays = $prop[1];
        $certificate = Subscription::select('*')->with('certificate')->where('certificate_id', $prop[0])->orderBy('id', 'desc')->first();

        if ( $certificate ) {
            $this->price = $certificate->price;
            $this->name = $certificate->certificate->project_type->type;
            $this->certificateId = $certificate->certificate->id;
            $this->icon = $certificate->certificate->project_type->image_icon;

            $difference = ($certificate->price * 100) / $certificate->certificate->price;
            $difference = abs(100 - $difference);
            $this->priceDifference = $difference;
            $this->differenceType = $certificate->price > $certificate->certificate->price ? 'inc' : 'dec';
        }

        $labels = $this->getLabels($this->maxDays);


        $this->emit('updateChart', [
            'data'     => $this->getRandomData($this->maxDays),
            'labels'   => $labels,
            'maxValue' => $this->maxValue,
            'stepSize' => round($this->maxValue / 10),
        ]);

    }

    public function render()
    {
        return view('livewire.admin.certificate.show-certificate', [
            'certificate'  => $this->certificate,
            'currentImage' => $this->currentImage(),
            'total_files'  => $this->total_files,
        ]);
    }

    protected function rules()
    {
        return [
            'file_path'   => ['nullable', 'array'],
            'file_path.*' => [File::types($this->acceptedMimes)->max(5 * 1024)], // Change the allowed file types and size as needed
        ];
    }

    public function storeFilePath()
    {
        $this->validate();

        foreach ($this->uploadedAllPath as $fKey => $fPath) {
            $full_name = $fPath->getClientOriginalName();
            $ext = pathinfo($full_name, PATHINFO_EXTENSION);
            $filename = 'certificate_' . time() . '_' . $fKey . '.' . $ext;
            $path = 'images/' . auth()->user()->id;
            $fPath->storeAs($path, $filename, 'public');
            $this->certificate->files()->create(['file_path' => $path . '/' . $filename]);
        }

        $this->uploadedAllPath = [];

        $this->certificate->name = $this->name;
        $this->certificate->description = $this->description;
        $this->certificate->lattitude = $this->lattitude;
        $this->certificate->longitude = $this->longitude;
        $this->certificate->verify_by = $this->verify_by;
        $this->certificate->registry_id = $this->registry_id;

        $this->certificate->save();

        if ( $this->certificate->files()->count() ) {
            foreach ($this->certificate->files() as $fileData) {
                $this->uploadedFiles[] = $fileData;
            }
        }

        session()->flash('success', 'Certificate updated successfully!');

        activity()
            ->performedOn($this->certificate)
            ->causedBy(auth()->user())
            ->log('Certificate <b> :subject.name </b> updated successfully!');
        $this->emit('reRenderParent');
    }

    public function approveCertificate($id)
    {
        $certificate = Certificate::find($id);
        $certificate->status = 2;
        $certificate->save();

        $sellCertificate = $certificate->sell_certificate()->where('is_main', true)->first();
        $sellCertificate->status = 2;
        $sellCertificate->save();
        $msg = 'Admin Approved for this carbon credit: <a href="'.route('sell.show.certificate',$sellCertificate->id).'">'.  $certificate->name. '</a>';
        $certificate->user->notify(new SendMessageNotification($msg));
        return redirect()->to("admin/certificates");
    }

    public function changeNameValue()
    {
        $this->certificate->name = $this->name;
        $this->certificate->save();
    }

    public function changeDescriptionValue()
    {
        $this->certificate->description = $this->description;
        $this->certificate->save();
    }

    private function getLabels($days)
    {
        $labels = [];
        if ( $days == 14 || $days == 30 ) {
            $startDate = Carbon::now()->subDays($days - 1);
            $endDate = Carbon::now();
            for ($i = $startDate; $i < $endDate; $i->addDay()) {
                $labels[] = $i->format('D');
            }
        } else {
            if ( $days == 180 ) {
                $counter = 6;
                $startDate = Carbon::now()->subMonths($counter - 1);
                $endDate = Carbon::now();
                for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                    $labels[] = $i->format('M');
                }
            } else {
                if ( $days == 365 ) {
                    $counter = 12;
                    $startDate = Carbon::now()->subMonths($counter - 1);
                    $endDate = Carbon::now();
                    for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                        $labels[] = $i->format('M');
                    }
                }
            }
        }
        return $labels;
    }

    private function getRandomData($days)
    {
        /*[
            { x: 0, y: 30, date: '1/1/22' },
            { x: 1, y: 18, date: '2/1/22' },
            { x: 2, y: 39, date: '3/1/22' },
            { x: 3, y: 70, date: '4/1/22' },
            { x: 4, y: 79, date: '5/1/22' },
            { x: 5, y: 65, date: '6/1/22' },
            { x: 6, y: 90, date: '7/1/22' },
            { x: 7, y: 30, date: '8/1/22' },
            { x: 8, y: 60, date: '9/1/22' },
            { x: 9, y: 60, date: '10/1/22' },
            { x: 10, y: 50, date: '11/1/22' },
            { x: 11, y: 79, date: '12/1/22' },
            { x: 12, y: 65, date: '13/1/22' },
            { x: 13, y: 90, date: '14/1/22' },
        ];*/

        $data = [];
        if ( $days == 14 || $days == 30 ) {
            $j = 0;
            $startDate = Carbon::now()->subDays($days - 1);
            $endDate = Carbon::now();

            $from = Carbon::now()->subDays($days - 1)->format('Y-m-d');
            $to = Carbon::now()->format('Y-m-d');
            $certificate = Subscription::select('*')->with('certificate')->where('certificate_id', $this->certificateId)
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->get();
            if ( $certificate->max('price') && $certificate->max('price') > 0 ) {
                $this->maxValue = $certificate->max('price');
                $this->stepSize = round($this->maxValue / 10);

            }
            $certificate = $certificate->groupBy('order_date');


            for ($i = $startDate; $i < $endDate; $i->addDay()) {
                $currentDate = $i->format('d/m/y');
                $value = 0;
                if ( isset($certificate[$currentDate]) && $certificate[$currentDate]->count() > 0 ) {
                    $value = $certificate[$currentDate]->avg('price');
                }
                $data[] = ['x' => $j, 'y' => $value, 'date' => $i->format('d/m/y')];
                $j++;
            }
        } else {
            if ( $days == 180 ) {
                $counter = 6;
                $startDate = Carbon::now()->subMonths($counter - 1);
                $endDate = Carbon::now();
                $from = Carbon::now()->subMonths($counter - 1)->format('Y-m-d');
                $to = Carbon::now()->format('Y-m-d');
                $certificate = Subscription::select('*')->with('certificate')->where('certificate_id', $this->certificateId)
                    ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                    ->get();
                if ( $certificate->max('price') && $certificate->max('price') > 0 ) {
                    $this->maxValue = $certificate->max('price');
                    $this->stepSize = round($this->maxValue / 10);
                }
                $certificate = $certificate->groupBy('order_month');
                $j = 0;
                for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                    $currentDate = $i->format('M');
                    $value = 0;
                    if ( isset($certificate[$currentDate]) && $certificate[$currentDate]->count() > 0 ) {
                        $value = $certificate[$currentDate]->avg('price');
                    }
                    $data[] = ['x' => $j, 'y' => $value, 'date' => $i->format('M')];
                    $j++;
                }
            } else {
                if ( $days == 365 ) {
                    $counter = 12;
                    $startDate = Carbon::now()->subMonths($counter - 1);
                    $endDate = Carbon::now();

                    $from = Carbon::now()->subMonths($counter - 1)->format('Y-m-d');
                    $to = Carbon::now()->format('Y-m-d');
                    $certificate = Subscription::select('*')->with('certificate')->where('certificate_id', $this->certificateId)
                        ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                        ->get();
                    if ( $certificate->max('price') && $certificate->max('price') > 0 ) {
                        $this->maxValue = $certificate->max('price');
                        $this->stepSize = round($this->maxValue / 10);
                    }
                    $certificate = $certificate->groupBy('order_month');

                    $j = 0;
                    for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                        $currentDate = $i->format('M');
                        $value = 0;
                        if ( isset($certificate[$currentDate]) && $certificate[$currentDate]->count() > 0 ) {
                            $value = $certificate[$currentDate]->avg('price');
                        }
                        $data[] = ['x' => $j, 'y' => $value, 'date' => $i->format('M')];
                        $j++;
                    }
                }
            }
        }
        return $data;
    }

    public function currentImage()
    {
        if ( is_array($this->file_path) ) {
            foreach ($this->file_path as $item) {
                if ( !in_array($item, $this->uploadedAllPath) ) {
                    $this->uploadedAllPath[] = $item;
                }
            }
        }


        $filePathUrl = collect($this->uploadedAllPath)->map(function ($item) {
            return $item->temporaryUrl();
        })->filter()->toArray();

        $uploadedPathUrl = collect($this->uploadedFiles)->map(function ($item) {
            return Storage::url($item);
        })->filter()->toArray();

        $files = count($filePathUrl) > 0 ? collect($uploadedPathUrl)->merge($filePathUrl)->filter() : $uploadedPathUrl;

        $this->total_files = count($files);

        return isset($files[$this->currentImageIndex]) ? $files[$this->currentImageIndex] : null;
    }

    public function prevImage()
    {
        if ( $this->currentImageIndex > 0 ) {
            $this->currentImageIndex--;
        }
    }

    public function nextImage()
    {
        if ( $this->currentImageIndex < $this->total_files - 1 ) {
            $this->currentImageIndex++;
        }
    }
}
