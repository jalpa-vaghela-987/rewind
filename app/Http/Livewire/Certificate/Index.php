<?php

namespace App\Http\Livewire\Certificate;

use App\Models\Certificate;
use App\Models\SellCertificate;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Http\Livewire\Table\Lists;


class Index extends Lists
{

    public Certificate $certificate;
    public $confirmingUserDeletion = false;
    public $selectedCertificate = null;
    public $title = null;
    public $amount = 0;
    public $unit = 0;
    public $total = 0;
    public $maxDays = '7D';
    public $currentValue = null;
    public $bank;
    protected $listeners = ['reRenderParent', 'callViewOrCancel', 'loadCertificates'];
    public $viewOrCancel;
    public $search;
    public $pricePerUnit;
    public $quantity;
    public $sellCertificate;
    public $price, $name, $icon, $priceDifference, $differenceType, $valueDiff;
    public $certificatePage;
    public $loadPage = false;

    public function render()
    {
        $this->user = auth()->user();
        /*$certificates = Certificate::where('user_id',$this->user->id)->with('sell_certificate')
            ->orderBy('id','desc')->paginate(15);*/
//        $sellCertificates = SellCertificate::with('certificate')
//            ->when($this->search, function ($builder) {
//                $builder->where(function ($builder) {
//                    $builder->orWhereHas('certificate', function ($query) {
//                        $query->where('name', 'like', '%' . $this->search . '%');
//                    });
//                    $builder->orWhere('remaining_units', 'like', '%' . $this->search . '%');
//                    $builder->orWhere('price_per_unit', 'like', '%' . $this->search . '%');
//                    $builder->orWhereHas('certificate.project_type', function ($query) {
//                        $query->where('type', 'like', '%' . $this->search . '%');
//                    });
//                    $builder->orWhereHas('certificate.country', function ($query) {
//                        $query->where('name', 'like', '%' . $this->search . '%');
//                    });
//                });
//            })
//            ->where('user_id',$this->user->id)
//            ->whereHas("certificate",function($q){
//                $q->where("deleted_at", null);
//            })
//            ->where('remaining_units', '>', 0)
//            ->orderBy('id', 'desc')->paginate(15);
//
//        $this->bank = BankDetail::where('user_id', auth()->id())->first();
//
//        foreach ($sellCertificates as $index => $certificate) {
//
//
//            $chartData = $this->getChartData($certificate, $this->maxDays);
//            $certificate->chart =   $chartData['data'];
//            //FOR TESTING     $certificate->chart =   [["x"=>0,"y"=>20,"date"=>"21/03/23"],["x"=>1,"y"=>50,"date"=>"21/03/23"],["x"=>2,"y"=>10,"date"=>"21/03/23"]];
//            $certificate->maxValue = $chartData['maxValue'];
//        }

        return view('livewire.certificate.index');
    }

    public function mount()
    {
        $this->user = auth()->user();
        $this->viewOrCancel = null;
        $this->lists = new Collection();
        $this->loadData();
    }

    public function getChartData($certificates, $days)
    {
        if ($days == '1D') {
            $days = 2;
        } elseif ($days == '7D') {
            $days = 7;
        } elseif ($days == '1M') {
            $days = 30;
        } elseif ($days == '6M') {
            $days = 6;
        }


        $data = [];
        $maxValue = 100;

        if ($days != 6) {
            $startDate = Carbon::now()->subDays($days - 1);
            $endDate = Carbon::now();
            $from = Carbon::now()->subDays($days - 1)->format('Y-m-d');
            $to = Carbon::now()->format('Y-m-d');
        } else {
            $startDate = Carbon::now()->subMonths($days - 1);
            $endDate = Carbon::now();
            $from = Carbon::now()->subMonths($days - 1)->format('Y-m-d');
            $to = Carbon::now()->format('Y-m-d');
        }
        $certificate = Subscription::select('*')
            ->with('certificate')
            ->where('certificate_id', $certificates->certificate_id);

        if ($certificates->is_main == 0) {
            $certificate = $certificate->where('sell_certificate_id', $certificates->id);
        }
        $certificate = $certificate->where('receiver_id', $this->user->id)
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->get();

        if ($certificate->max('price') && $certificate->max('price') > 0) {
            $maxValue = number_format($certificate->max('price'), 2);
        }

        if ($days != 6) {
            $certificate = $certificate->groupBy('order_date');
        } else {
            $certificate = $certificate->groupBy('order_month');
        }
        $j = 0;
        if ($certificate) {

            if ($days != 6) {
                for ($i = $startDate; $i < $endDate; $i->addDay()) {
                    $currntDate = $i->format('d/m/y');
                    $value = 0;
                    if (isset($certificate[$currntDate]) && $certificate[$currntDate]->count() > 0) {
                        $value = number_format($certificate[$currntDate]->avg('price'), 2);
                    }
                    $data[] = ['x' => $j, 'y' => $value, 'date' => $i->format('d/m/y')];
                    $j++;
                }
            } else {
                for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                    $currntDate = $i->format('M');
                    $value = 0;
                    if (isset($certificate[$currntDate]) && $certificate[$currntDate]->count() > 0) {
                        $value = number_format($certificate[$currntDate]->avg('price'), 2);
                    }
                    $data[] = ['x' => $j, 'y' => $value, 'date' => $i->format('M')];
                    $j++;
                }

            }

            return ['data' => $data, 'maxValue' => $maxValue];
        }


        return [];
    }

    public function reRenderParent()
    {
        //ToDo: need to improve
        // return redirect()->route('sell');
        $this->page = 1;
        $this->lists = new Collection();
        $this->loadData();
        $this->render();

    }

    public function openModal(Certificate $certificate)
    {
        $this->confirmingUserDeletion = !$this->confirmingUserDeletion;
        $this->selectedCertificate = $certificate;
        $this->title = $certificate->name;
        $this->amount = $certificate->price_per_unit;
        $this->unit = $certificate->quantity;
        $this->total = $this->amount * $this->unit;
    }

    public function closeModal(Certificate $certificate)
    {
        $this->confirmingUserDeletion = !$this->confirmingUserDeletion;
    }

    public function showCertificate($id)
    {
        return redirect()->to("sell/certificate/$id");
    }

    public function changeEvent($value)
    {
        $this->maxDays = $value;
        $this->mount();
        $this->render();
    }

    public function openCancelSellCertificateModal($certificate_id)
    {
        $this->emit('openCancelSellCertificateModal', $certificate_id);
    }

    public function openDetailPage($id)
    {
        return redirect()->route('sell.show.certificate', $id);
    }

    public function callViewOrCancel()
    {
        $data = explode('_', $this->viewOrCancel);
        $this->viewOrCancel = null;
        if (count($data) == 2) {
            if ($data[0] == 'view') {
                $this->openDetailPage($data[1]);
            } else {
                $certificate = SellCertificate::where('id', $data[1])
                    ->first();
                if ($certificate) {
                    $this->emit('openCancelSellCertificateModal', $data[1]);
                }
            }
        }
    }

    public function openSellModal(SellCertificate $sellCertificate)
    {
        $this->selectedCertificate = $sellCertificate;
        $this->emit('openSellModal', $this->selectedCertificate->id);
    }

    public function loadData()
    {
        $sell_certificates = SellCertificate::with('certificate', 'certificate.project_type')
            ->when($this->search, function ($builder) {
                $builder->where(function ($builder) {
                    $builder->orWhereHas('certificate', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
                    $builder->orWhere('remaining_units', 'like', '%' . $this->search . '%');
                    $builder->orWhere('price_per_unit', 'like', '%' . $this->search . '%');
                    $builder->orWhereHas('certificate.project_type', function ($query) {
                        $query->where('type', 'like', '%' . $this->search . '%');
                    });
                    $builder->orWhereHas('certificate.country', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->where('user_id', $this->user->id)
            ->whereHas("certificate", function ($q) {
                $q->where("deleted_at", null);
            })
            ->where('remaining_units', '>', 0)
            ->orderBy('id', 'desc')
            ->take($this->perPage*$this->page)
            ->get();

        $this->loadDataList($sell_certificates);
    }

    public function updatedSearch(){
        $this->page = 1;
        $this->loadData();
    }
}
