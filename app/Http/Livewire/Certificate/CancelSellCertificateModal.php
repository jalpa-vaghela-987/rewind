<?php

namespace App\Http\Livewire\Certificate;

use App\Models\Certificate;
use App\Models\SellCertificate;
use App\Notifications\SendMessageNotification;
use Livewire\Component;

class CancelSellCertificateModal extends Component
{
    protected $listeners    =   ['openCancelSellCertificateModal'];
    public SellCertificate $sell_certificate;
    public $showModal       =   false;
    public $is_agree        =   false;
    public $certificate_id;
    protected $rules = [
        'is_agree' => 'in:1',
    ];
    protected $messages = [
        'is_agree.in'     => 'Please accept the terms',
    ];

    public function render()
    {
        return view('livewire.certificate.cancel-sell-certificate-modal');
    }
    /**
     *
     */
    public function openCancelSellCertificateModal($certificate_id){
        $sell_certificate    =   SellCertificate::find($certificate_id);

        if($sell_certificate){
            $this->sell_certificate  =   $sell_certificate;
            $this->showModal = true;
        }else{
            $type   =   "error";
            $msg    =   "Technical error";
            $this->emitTo('flash-component', 'flashMessage',['type' => $type, 'msg' => $msg]);
//            $this->emitTo("MyPortfolio","reRenderComponent");
        }
    }

    public function closeCancelSellCertificateModal(){
        $this->emit('reRenderParent');
        $this->showModal = false;
    }
    /**
     *
    */
    public function deleteCancelSellCertificate(){
        $units = $this->sell_certificate->remaining_units;
        $this->sell_certificate->cancelSellCertificate($this->sell_certificate);
        $type = 'success';
        $msg = 'Certificate Canceled successfully!';
        activity()
            ->performedOn($this->sell_certificate)
            ->causedBy(auth()->user())
            ->log('A quantity of '.$units.' <b>:subject.certificate.project_type.type</b> has been Deleted');

        $this->emitTo('flash-component', 'flashMessage',['type' => $type, 'msg' => $msg]);
        $this->closeCancelSellCertificateModal();
        $this->sendFollowerNotify( $this->sell_certificate->certificate);
        return redirect()->route("sell");
    }

    public function sendFollowerNotify($certificate)
    {
        $followers = $certificate->followers()->get();
        $message='Carbon Credit:'. $certificate->name .' is canceled' ;
        foreach ($followers as $follower){
            $follower->user->notify(new SendMessageNotification($message));
        }
    }
}
