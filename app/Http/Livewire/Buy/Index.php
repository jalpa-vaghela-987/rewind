<?php

namespace App\Http\Livewire\Buy;

use App\Mail\BidApprovalMail;
use App\Models\BankDetail;
use App\Models\Bid;
use App\Models\CardDetail;
use App\Models\Certificate;
use App\Models\CreditFollower;
use App\Models\SellCertificate;
use App\Models\BuyPriceAlert;
use App\Models\Subscription;
use App\Services\StripeHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Collection;
use App\Http\Livewire\Table\Lists;

class Index extends Lists
{


    protected $listeners = ['openModal', 'setSelectedDate', 'getBuyToggleData','reRenderParent'=>'reRenderComponent','makeDisableIdZero'];
    public $openPriceAlertModal = false;
    public $showDateModal = false;
    public $showData = 'quantity';
    public $bidShowData = 'quantity';
    public $activeTab = 'buy';
    public $selectedCertificate = null;
    public $cardHolderName = null;
    public $paymentMethod = null;
    public $title = null;
    public $pricePerUnit = 0;
    public $cost = 0;
    public $units = 0;
    public $bid_units = 0;
    public $rate = 0;
    public $amount = 0;
    public $showModal = false;
    public $card, $cards, $primary_card_id;
    public $expiration_date;
    public $price_difference = 0;
    public $amount_difference = 0;
    public $currentValue = null;
    public $user;
    public $search;
//    public $certificates = null;
    public $maxDays = '7D';
    public $buyToggleValue = null;
    public $disable_id=0;
    public function render()
    {
        $user = auth()->user();

        $sellCertificates = SellCertificate::with('certificate','followers')
            ->when($this->search, function ($builder) {
                $builder->where(function ($builder) {
                    $builder->orWhereHas('certificate', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
                    $builder->orWhere('remaining_units', 'like', '%' . $this->search . '%');
                    $builder->orWhereHas('certificate.project_type', function ($query) {
                        $query->where('type', 'like', '%' . $this->search . '%');
                    });
                    $builder->orWhereHas('certificate.country', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->where('remaining_units', '>', 0)
            ->where('is_main', 0)
            ->where('user_id', '!=', $user->id)
            ->orderBy('id', 'desc')
            ->paginate(15);

        foreach ($sellCertificates as $index => $certificate) {
            $chartData = $this->getChartData($certificate->id, $this->maxDays);
            $certificate->chart = $chartData['data'];
            //FOR TESTING     $certificate->chart =   [["x"=>0,"y"=>20,"date"=>"21/03/23"],["x"=>1,"y"=>50,"date"=>"21/03/23"],["x"=>2,"y"=>10,"date"=>"21/03/23"]];
            $certificate->maxValue = $chartData['maxValue'];

            //price calculation
            $certificate->price_average = $certificate->priceCalculation($certificate)->price_average;
        }

        $intent = (new StripeHelper($user))->createStripeSetupIntent();

        return view('livewire.buy.index', compact('sellCertificates', 'intent'));
    }

    public function getChartData($certificateId, $days)
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
            ->where('sell_certificate_id', $certificateId)
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

    public function mount()
    {
        $this->user = auth()->user();
        $this->resetValidation();
        $this->page = 1;
        $this->lists = new Collection();
        $this->loadData();
    }

    public function openModal(SellCertificate $sellCertificate)
    {
        $this->disable_id   =   $sellCertificate->id;
        $sender = auth()->user();
        if (!$sender->phone_verified && $sender->phone) {
            $this->emit('openCloseResendVerificationSMS');
        } else {
            $this->selectedCertificate = $sellCertificate;
            $this->emit('openBuyBidModal',$this->selectedCertificate->id);
        }
    }
    public function makeDisableIdZero(){
        $this->disable_id   =   0;
    }
    /**
     * ASKED TO REMOVE THIS FEATURE
     */
    // public function switchShowData()
    // {
    //     $this->showData = ($this->showData == 'cost') ? 'units' : 'cost';
    // }
    public function clear()
    {
        $this->disable_id = 0;
        $this->pricePerUnit = 0;
        $this->cost = 0;
        $this->units = 0;
        $this->card = null;
        $this->amount = 0;
        $this->rate = 0;
        $this->bid_units = 0;
        $this->expiration_date = null;
        $this->amount_difference = 0;
    }
    /**
     * separated to a component (BuyBidModal)
     */
    public function clearCalculation()
    {
        $this->pricePerUnit = 0;
        $this->cost = 0;
        $this->units = 0;
        $this->amount_difference = 0;
    }

    public function setCurrentValue($time)
    {
        if ($time == '7d') {

        } else if ($time == '1m') {

        } else if ($time == '6m') {

        } else {
            //get date for one day
        }
    }

    // public function setSelectedDate($date)
    // {
    //     // $this->expiration_date = $date;
    //     $this->resetValidation();
    //     $this->toggleDateModal();
    // }

    // public function openSetPriceAlertModal(SellCertificate $sellCertificate)
    // {
    //     $this->clearCalculation();
    //     $this->mount();
    //     $this->selectedCertificate = $sellCertificate;
    //     $this->emit('openPriceAlertModal',$this->selectedCertificate->id);

    // }

    public function closeSetPriceAlertModal()
    {
        $this->openPriceAlertModal = false;
//        $this->clear();
    }

    public function openPriceAlertModal($id=""){
        $sellCertificate = SellCertificate::find((int)$id);
        $this->emit('openPriceAlertModal', $sellCertificate->id);
    }

    public function reRenderComponent(){
        $this->clear();
        $this->page = 1;
        $this->lists = new Collection();
        $this->loadData();
        $this->render();
    }

    public function followCredit(SellCertificate $sellCertificate)
    {
        $creditFollower = CreditFollower::where(['sell_certificate_id' => $sellCertificate->id, 'user_id' => auth()->id()])->first();
        if ($creditFollower) {
            $creditFollower->delete();
            $type = 'success';
            $msg = 'You had unfollowed this carbon credits:' . $sellCertificate->certificate->name;
        } else {
            $creditFollower = new CreditFollower;
            $creditFollower->user_id = auth()->id();
            $creditFollower->certificate_id = $sellCertificate->certificate_id;
            $creditFollower->sell_certificate_id = $sellCertificate->id;
            $creditFollower->save();

            $type = 'success';
            $msg = 'You had followed this carbon credits:' . $sellCertificate->certificate->name;

        }

        $this->reRenderComponent();
        $this->emitTo('flash-component', 'flashMessage', ['type' => $type, 'msg' => $msg]);
    }

    public function loadData()
    {
        $sellCertificates = SellCertificate::with('certificate', 'certificate.project_type')
            ->when($this->search, function ($builder) {
                $builder->where(function ($builder) {
                    $builder->orWhereHas('certificate', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
                    $builder->orWhere('remaining_units', 'like', '%' . $this->search . '%');
                    $builder->orWhereHas('certificate.project_type', function ($query) {
                        $query->where('type', 'like', '%' . $this->search . '%');
                    });
                    $builder->orWhereHas('certificate.country', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->where('remaining_units', '>', 0)
            ->where('is_main', 0)
            ->where('user_id', '!=', auth()->user()->id)
            ->orderBy('id', 'desc')
            ->take($this->perPage*$this->page)
            ->get();

        $this->loadDataList($sellCertificates);
//        dump($this->page);
    }

    public function updatedSearch(){
        $this->page = 1;
        $this->loadData();
    }
}
