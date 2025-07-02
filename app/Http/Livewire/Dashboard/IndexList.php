<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\SellCertificate;
use Livewire\Component;

class IndexList extends Component
{
    protected $listeners = ['makeDisableIdZero'];
    public $disable_id=0;
    public $selectedCertificate = null;
    public $user;
    public function render()
    {
        $this->user     =   auth()->user();
        $certificates   =   SellCertificate::with('certificate')
            ->withCount('subscriptions')
            ->where('user_id','!=',auth()->user()->id)
            ->where('remaining_units',">",0)
            ->where('is_main',0)
            ->orderby('subscriptions_count', 'DESC')
            ->get();

        foreach($certificates as $certificate){
            $certificate->price_average = $certificate->priceCalculation($certificate)->price_average;
            $certificate->price_difference = $certificate->priceCalculation($certificate)->price_difference;
        }
        return view('livewire.dashboard.index-list',compact('certificates'));
    }

    public function certificateSelected($cId,$dur){
        $prop = [$cId,$dur];
        $this->emit('certificate-selected',$prop);
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
        $this->disable_id   =  0;
    }
    public function openPriceAlertModal($id=""){
        $sellCertificate = SellCertificate::find((int)$id);
        $this->emit('openPriceAlertModal', $sellCertificate->id);
    }
    public function makeDisableIdZero(){
        $this->disable_id   =   0;
    }
}
