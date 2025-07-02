<?php

namespace App\Http\Livewire\Certificate;

use App\Models\BankDetail;
use App\Models\Certificate;
use App\Models\SellCertificate;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\SendMessageNotification;
use Carbon\Carbon;
use Livewire\Component;

class SellCertificateModal extends Component
{
    protected $listeners = ['openSellModal', 'addBankForSell'];
    public $showSellCertificateModal=false;
    public $sellCertificate;
    public $maxQuantity;
    public $amount = 0;
    public $unit = 0;
    public $total = 0;
    public $bank;
    public $pricePerUnit;
    public $quantity;

    public function render()
    {

        $this->bank = BankDetail::where('user_id', auth()->id())->first();
        return view('livewire.certificate.sell-certificate-modal');
    }

    public function openSellModal(SellCertificate $sellCertificate){
        $this->showSellCertificateModal = true;
        $this->sellCertificate = $sellCertificate;
        $this->amount = $this->sellCertificate->price_per_unit;
        $this->unit = $this->sellCertificate->remaining_units;
        $this->total = $this->amount * $this->unit;
        $this->pricePerUnit = $this->sellCertificate->price_per_unit;

        $today = Carbon::now()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');

        $pricePerUnit = $this->pricePerUnit;
        $this->priceDifference = 0;
        $this->valueDiff = 0;
        $todaySubscription = Subscription::where('certificate_id', $this->sellCertificate->certificate_id)->where('created_at', 'like', '%' . $today . '%')->first();
        $yesterdaySubscription = Subscription::where('certificate_id', $this->sellCertificate->certificate_id)->where('created_at', 'like', '%' . $yesterday . '%')->first();
        if (!empty($todaySubscription) && !empty($yesterdaySubscription)) {
            $todayPrice = $todaySubscription->amount / $todaySubscription->quantity;
            $yesterdayPrice = $yesterdaySubscription->amount / $yesterdaySubscription->quantity;
            $amountDifference = ($todayPrice - $yesterdayPrice);
            $price_average = ($amountDifference * 100) / $pricePerUnit;

            $this->priceDifference = $price_average;
            $this->differenceType = $todayPrice > $yesterdayPrice ? 'inc' : 'dec';
        }

        $this->setMaxQuantity();
    }

    public function setMaxQuantity()
    {
        $mainSellCertificate = $this->sellCertificate->getMainSellCertificate();

        if ($this->sellCertificate->is_main) {
            return $this->maxQuantity = $mainSellCertificate->remaining_units;
        }
        $this->maxQuantity = $mainSellCertificate->remaining_units + $this->sellCertificate->remaining_units;

    }

    public function decrease($type)
    {
        $this->$type--;
        $this->total = $this->pricePerUnit * $this->unit;
        if ($this->$type <= 0) {
            $this->$type = 1;
            $this->addError('error', 'Your ' . $type . ' must be greater than 0');
        }
    }

    public function increase($type)
    {
        if ($type == 'quantity' || $type == 'pricePerUnit') {
            if ($this->quantity == $this->maxQuantity) {
                $this->addError('error', 'Your ' . $type . ' must be less than or equal to ' . $this->maxQuantity);
            } else {
                $this->$type++;
            }
        } else {
            $this->$type++;
        }
        $this->total = $this->pricePerUnit * $this->unit;
    }

    public function increaseAmount()
    {
        if ($this->unit + 1 <= $this->maxQuantity) {
            $this->unit = $this->unit + 1;
        }
        $this->total = $this->pricePerUnit * $this->unit;
    }

    public function decreaseAmount()
    {
        if ($this->unit > 1) {
            $this->unit = $this->unit - 1;
        }
        $this->total = $this->pricePerUnit * $this->unit;
    }

    public function closeSellModal(Certificate $certificate)
    {
        $this->showSellCertificateModal = false;
    }

    public function sellCertificate()
    {
        $existedSellCertificate = SellCertificate::where('price_per_unit', $this->pricePerUnit)
            ->where('status', SellCertificate::STATUS_ON_SELL)
            ->whereNot('id',$this->sellCertificate->id)
            ->where('certificate_id', $this->sellCertificate->certificate_id)
            ->first();

        if ($existedSellCertificate) {
            $existedSellCertificate->remaining_units += $this->unit;
            $existedSellCertificate->units += $this->unit;
            $existedSellCertificate->save();
            $soldCertificate = $existedSellCertificate;
        } else {
            $sellCertificate = new SellCertificate();
            $sellCertificate->certificate_id = $this->sellCertificate->certificate_id;
            $sellCertificate->user_id = $this->sellCertificate->user_id;
            $sellCertificate->units = $this->unit;
            $sellCertificate->remaining_units = $this->unit;
            $sellCertificate->price_per_unit = $this->pricePerUnit;
            $sellCertificate->status = 3;
            $sellCertificate->save();
            $soldCertificate = $sellCertificate;
        }
        $mainSellCertificate = $this->sellCertificate->getMainSellCertificate();
        $mainSellCertificate->remaining_units = abs($mainSellCertificate->remaining_units - $this->unit);
        $mainSellCertificate->save();

        activity()
            ->performedOn($soldCertificate)
            ->causedBy(auth()->user())
            ->log('A quantity of '.$this->unit.' <b>:subject.certificate.project_type.type</b> has been Sold');

        $this->showSellCertificateModal = false;

        $msg = 'New carbon credit is added to buy: <a href="'.route('buy.show.certificate',$soldCertificate->id).'">'.  $soldCertificate->certificate->name. '</a>';
        $users = User::whereNotIn('id',[1,auth()->id()])->where('status',1)->get();
        foreach ($users as $user){
            $user->notify(new SendMessageNotification($msg,'buy'));
        }

        return redirect()->route("sell");

    }

    public function unitChange(){
        if ($this->unit <= $this->maxQuantity) {
            $this->total = $this->pricePerUnit * $this->unit;
        }else{
            $this->addError('error', 'Your quantity must be less than or equal to ' . $this->maxQuantity);
        }

    }

    public function addBankForSell($bank) {
        $this->bank = $bank;
    }
}
