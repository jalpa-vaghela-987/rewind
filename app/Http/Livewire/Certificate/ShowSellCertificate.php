<?php

namespace App\Http\Livewire\Certificate;

use App\Models\BankDetail;
use App\Models\Certificate;
use App\Models\BuyPriceAlert;
use App\Models\SellCertificate;
use App\Models\Subscription;
use App\Notifications\SendMessageNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ShowSellCertificate extends Component
{
    public $certificate;
    public $sellCertificate;
    public $showSellCertificateModal = false;
    public $updateUnitPrice = false;
    public $updateQuantity = false;
    public $amount = 0;
    public $unit = 0;
    public $total = 0;
    public $unitprice;
    public $pricePerUnit;
    public $quantity;
    public $maxQuantity;

    public $price, $name, $icon, $priceDifference, $differenceType, $valueDiff;

    public array $dataset = [];
    public array $labels = [];
    public $maxDays = 14;
    public $maxValue = 100;
    public $stepSize = 10;
    public $bank;

    public $user;

    public $currentImageIndex = 0;
    public $total_files = 0;

    protected $listeners = [
        'certificate-selected' => 'certificateSelected',
        'reRenderComponent'    => 'reRenderComponent',
    ];

    public function render()
    {

        return view('livewire.certificate.show-sell-certificate', [
            'certificate'  => $this->certificate,
            'currentImage' => $this->currentImage(),
            'total_files'  => $this->total_files,

        ]);
    }

    public function mount($id)
    {
        $this->user = auth()->user();
        $this->sellCertificate = SellCertificate::where(['id' => $id, 'user_id' => auth()->id()])->first();

        $this->certificate = $this->sellCertificate->certificate;
        if ( !$this->sellCertificate ) {
            abort(404);
        }
        $this->bank = BankDetail::where('user_id', auth()->id())->first();
        $soldCertificate = Subscription::select('*')
            ->with('certificate')
            ->where('certificate_id', $this->sellCertificate->certificate_id)
            ->orderBy('id', 'desc')
            ->first();

        if ( $soldCertificate ) {
            $this->price = $soldCertificate->amount;
            $this->name = $soldCertificate->certificate->project_type->type;
            $this->icon = $soldCertificate->certificate->project_type->image_icon;

            $difference = ($soldCertificate->amount * 100) / $soldCertificate->certificate->price;
            $difference = abs(100 - $difference);
            $this->priceDifference = $difference;
            $this->differenceType = $soldCertificate->price > $soldCertificate->certificate->price ? 'inc' : 'dec';
        } else {
            $this->price = $this->certificate->price;
        }

        $today = Carbon::now()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');

        $pricePerUnit = $this->sellCertificate->price_per_unit;
        $todaySubscription = Subscription::where('certificate_id', $this->certificate->id)->where('created_at', 'like', '%' . $today . '%')->first();
        $yesterdaySubscription = Subscription::where('certificate_id', $this->certificate->id)->where('created_at', 'like', '%' . $yesterday . '%')->first();
        if ( !empty($todaySubscription) && !empty($yesterdaySubscription) ) {
            $todayPrice = $todaySubscription->amount / $todaySubscription->quantity;
            $yesterdayPrice = $yesterdaySubscription->amount / $yesterdaySubscription->quantity;
            $amountDifference = ($todayPrice - $yesterdayPrice);
            $price_average = ($amountDifference * 100) / $pricePerUnit;

            $this->priceDifference = $price_average;
            $this->differenceType = $todayPrice > $yesterdayPrice ? 'inc' : 'dec';
        }

        $this->labels = $this->getLabels($this->maxDays);
        $this->dataset = $this->getRandomData($this->maxDays);
    }

    public function certificateSelected($prop)
    {
        $this->maxDays = $prop[1];
        $certificate = Subscription::select('*')
            ->with('certificate')
            ->where('certificate_id', $prop[0])
            ->where('receiver_id', $this->user->id)
            ->orderBy('id', 'desc')
            ->first();

        if ( $certificate ) {
            $this->price = $certificate->price;
            $this->name = $certificate->certificate->project_type->type;
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


    public function openModal(SellCertificate $sellCertificate)
    {
        $this->selectedCertificate = $sellCertificate;
        $this->emit('openSellModal', $this->selectedCertificate->id);
    }

    public function setMaxQuantity()
    {
        $mainSellCertificate = $this->sellCertificate->getMainSellCertificate();

        if ( $this->sellCertificate->is_main ) {
            return $this->maxQuantity = $mainSellCertificate->remaining_units;
        }
        $this->maxQuantity = $mainSellCertificate->remaining_units + $this->sellCertificate->remaining_units;

    }

    public function closeModal(Certificate $certificate)
    {
        $this->showSellCertificateModal = !$this->showSellCertificateModal;
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
            $certificate = Subscription::select('*')->with('certificate')
                //->where('sell_certificate_id', $this->sellCertificate->id)
                ->where('certificate_id', $this->sellCertificate->certificate_id)
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->get();
            if ( $certificate->max('price') && $certificate->max('price') > 0 ) {
                $this->maxValue = $certificate->max('price');
                $this->stepSize = round($this->maxValue / 10);

            }
            $certificate = $certificate->groupBy('order_date');


            for ($i = $startDate; $i < $endDate; $i->addDay()) {
                $currntDate = $i->format('d/m/y');
                $value = 0;
                if ( isset($certificate[$currntDate]) && $certificate[$currntDate]->count() > 0 ) {
                    $value = $certificate[$currntDate]->avg('price');
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
                $certificate = Subscription::select('*')->with('certificate')
                    //->where('sell_certificate_id', $this->sellCertificate->id)
                    ->where('certificate_id', $this->sellCertificate->certificate_id)
                    //->where('receiver_id', $this->user->id)
                    ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                    ->get();
                if ( $certificate->max('price') && $certificate->max('price') > 0 ) {
                    $this->maxValue = $certificate->max('price');
                    $this->stepSize = round($this->maxValue / 10);
                }
                $certificate = $certificate->groupBy('order_month');
                $j = 0;
                for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                    $currntDate = $i->format('M');
                    $value = 0;
                    if ( isset($certificate[$currntDate]) && $certificate[$currntDate]->count() > 0 ) {
                        $value = $certificate[$currntDate]->avg('price');
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
                    $certificate = Subscription::select('*')->with('certificate')
                        //->where('sell_certificate_id', $this->sellCertificate->id)
                        ->where('certificate_id', $this->sellCertificate->certificate_id)
                        //->where('receiver_id', $this->user->id)
                        ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                        ->get();
                    if ( $certificate->max('price') && $certificate->max('price') > 0 ) {
                        $this->maxValue = $certificate->max('price');
                        $this->stepSize = round($this->maxValue / 10);
                    }
                    $certificate = $certificate->groupBy('order_month');

                    $j = 0;
                    for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                        $currntDate = $i->format('M');
                        $value = 0;
                        if ( isset($certificate[$currntDate]) && $certificate[$currntDate]->count() > 0 ) {
                            $value = $certificate[$currntDate]->avg('price');
                        }
                        $data[] = ['x' => $j, 'y' => $value, 'date' => $i->format('M')];
                        $j++;
                    }
                }
            }
        }
        return $data;
    }

    public function quantityModal($quantity)
    {
        $this->updateQuantity = !$this->updateQuantity;
        $this->quantity = $quantity;
        $this->setMaxQuantity();
    }

    public function closeQuantityModal()
    {
        $this->updateQuantity = !$this->updateQuantity;
    }

    public function unitModal($pricePerUnit)
    {
        $this->updateUnitPrice = !$this->updateUnitPrice;
        $this->unitprice = $pricePerUnit;
    }

    public function closeUnitModal()
    {
        $this->updateUnitPrice = !$this->updateUnitPrice;
    }

    public function decrease($type)
    {
        $this->$type--;
        $this->total = $this->pricePerUnit * $this->unit;
        if ( $this->$type <= 0 ) {
            $this->$type = 1;
            $this->addError('error', 'Your ' . $type . ' must be greater than 0');
        }
    }

    public function increase($type)
    {
        if ( $type == 'quantity' ) {
            if ( $this->quantity == $this->maxQuantity ) {
                $this->addError('error', 'Your ' . $type . ' must be less than or equal to ' . $this->maxQuantity);
            } else {
                $this->$type++;
            }
        } else {
            $this->$type++;
        }
        $this->total = $this->pricePerUnit * $this->unit;
    }

    public function saveQuantity()
    {
        if ( $this->quantity > $this->maxQuantity ) {
            $this->addError('error', 'Your quantity must be less than or equal to ' . $this->maxQuantity);
            return;
        }

        if ( $this->sellCertificate->is_main ) {
            $this->certificate->quantity = $this->quantity;
            $this->certificate->save();
            $this->sellCertificate->units = $this->quantity;
            $this->sellCertificate->remaining_units = $this->quantity;
            $this->sellCertificate->save();
        } else {
            //if yes then we can't decrease the quantity less than conducted quantity in that entries
            if ( $this->sellCertificate->remaining_units > $this->quantity ) {
                $quantityDifference = $this->sellCertificate->remaining_units - $this->quantity;
                // decrease the quantity from child sell certificate
                $this->sellCertificate->units -= $quantityDifference;
                // decrease the remaining_quantity from child sell certificate
                $this->sellCertificate->remaining_units = $this->quantity;
                $this->sellCertificate->save();
                // increase the remaining_units in main sell certificate
                $mainCertificate = $this->sellCertificate->getMainSellCertificate();
                $mainCertificate->remaining_units += $quantityDifference;
                $mainCertificate->save();


            } else {
                $quantityDifference = $this->quantity - $this->sellCertificate->remaining_units;
                // increase the quantity in child sell certificate
                $this->sellCertificate->units += $quantityDifference;
                // increase the remaining_quantity in child sell certificate
                $this->sellCertificate->remaining_units = $this->quantity;
                $this->sellCertificate->save();
                // decrease the remaining_units from main sell certificate
                $mainCertificate = $this->sellCertificate->getMainSellCertificate();
                $mainCertificate->remaining_units -= $quantityDifference;
                $mainCertificate->save();
            }
        }
        $this->certificate->quantity = $this->quantity;
        $this->certificate->save();
        $message = 'Quantity has been updated success';
        $this->sendFollowerNotify($this->sellCertificate,$this->quantity,'quantity');
        $this->closeQuantityModal();
        $this->emitTo('flash-component', 'flashMessage', ['type' => 'success', 'msg' => $message]);
    }

    public function saveUnitPrice()
    {
        if ( $this->sellCertificate->is_main ) {
            $this->certificate->price = $this->certificate->quantity * $this->unitprice;
            $this->certificate->save();
            $this->sellCertificate->price_per_unit = $this->unitprice;
            $this->sellCertificate->save();
        } else {
            $existedSellCertificate = SellCertificate::where('price_per_unit', $this->unitprice)
                ->where('status', SellCertificate::STATUS_ON_SELL)
                ->where('certificate_id', $this->sellCertificate->certificate_id)
                ->whereNot('id', $this->sellCertificate->id)
                ->first();

            if ( $existedSellCertificate ) {
                if ( $this->sellCertificate->subscriptions->count() > 0 || $this->sellCertificate->bids->count() > 0 ) {
                    //Important Note: Don't change the sequence of below 6 lines
                    $existedSellCertificate->remaining_units += $this->sellCertificate->remaining_units;
                    $existedSellCertificate->units += $this->sellCertificate->remaining_units;
                    $this->sellCertificate->units -= $this->sellCertificate->remaining_units;
                    $this->sellCertificate->remaining_units = 0;
                    $existedSellCertificate->save();
                    $this->sellCertificate->save();

                } else {
                    //Important Note: Don't change the sequence of below 4 lines
                    $existedSellCertificate->remaining_units += $this->sellCertificate->remaining_units;
                    $existedSellCertificate->units += $this->sellCertificate->remaining_units;
                    $existedSellCertificate->save();
                    $this->setBuyPriceAlert($existedSellCertificate);
                    $this->sellCertificate->delete();
                    return redirect()->route('sell');

                }
            } else {
                $this->sellCertificate->price_per_unit = $this->unitprice;
                $this->sellCertificate->save();
            }
        }

        $this->setBuyPriceAlert($this->sellCertificate);
        $this->sendFollowerNotify($this->sellCertificate,$this->unitprice,'price');


        $message = 'Unit Price has been updated success';
        $this->closeUnitModal();
        $this->emitTo('flash-component', 'flashMessage', ['type' => 'success', 'msg' => $message]);
    }

    public function setBuyPriceAlert($sellCertificate)
    {
        if ( $sellCertificate && $sellCertificate->remaining_units > 0 ) {
            $buyPriceAlerts = BuyPriceAlert::where([
                'certificate_id' => $this->certificate->id,
                'amount'         => $this->unitprice,
            ])->get();
            if ( $buyPriceAlerts ) {
                foreach ($buyPriceAlerts as $buyPriceAlert) {
                    if ( $buyPriceAlert->percentage >= 0 ) {
                        $message = "Certificate " . $this->certificate->name . " raised by " . $buyPriceAlert->percentage . "%";
                    } else {
                        $message = "Certificate " . $this->certificate->name . " fallen by " . $buyPriceAlert->percentage . "%";
                    }
                    $buyPriceAlert->user->notify(new SendMessageNotification($message,'buy'));
                }
            }
        }
    }

    public function sendFollowerNotify($sellCertificate,$value,$type)
    {
        $followers = $sellCertificate->followers()->get();
        if($type== 'price'){
            $message='Carbon Credit:'. $this->certificate->name .' Price Changed to $'.$value ;
        }else{
            $message='Carbon Credit:'. $this->certificate->name .' Quantity Changed to '.$value ;
        }
        foreach ($followers as $follower){
            $follower->user->notify(new SendMessageNotification($message));
        }
    }

    public function openCancelSellCertificateModal($certificate_id)
    {
        $this->emit('openCancelSellCertificateModal', $certificate_id);
    }

    public function reRenderComponent()
    {
        $this->mount($this->sellCertificate->id);
        $this->render();
    }

    public function currentImage()
    {
        $files = collect($this->sellCertificate->certificate->files()->pluck('file_path')->toArray())->map(function ($item) {
            return Storage::url($item);
        })->filter()->toArray();

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
