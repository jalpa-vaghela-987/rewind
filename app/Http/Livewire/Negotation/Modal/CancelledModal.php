<?php

namespace App\Http\Livewire\Negotation\Modal;

use App\Mail\BidVerifyMail;
use App\Models\Bid;
use App\Models\CounterOffer;
use App\Models\SellCertificate;
use App\Notifications\SendMessageNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class CancelledModal extends Component
{
    protected $listeners = ['reRenderParent'=>'reRenderComponent'];
    public $cancelledModal = false;
    public $selectedBid = null;
    public $isDisabled = false;
    public function render()
    {
        return view('livewire.negotation.modal.cancelled-modal');
    }

    public function mount()
    {
        $this->cancelledModal = !$this->cancelledModal;
    }

    public function closeModal()
    {
        $this->cancelledModal = false;
        //*******TEMPORARY ADD REFRESH ALL***********
        $this->emit('refreshAll');
    }

    public function reRenderComponent(){
        $this->mount();
        $this->render();
    }

    public function cancelOffer()
    {
        $this->isDisabled = true;
        $bid = $this->selectedBid;
        $sellCertificateId = $bid->sell_certificate_id;

        //cancel sell certificate
        $this->cancelSellCertificateById($sellCertificateId);

        //cancel other users sell certificate
        $this->cancelRelativeOffers($sellCertificateId);


        $this->emitTo('flash-component', 'flashMessage',['type' => 'success', 'msg' => "Your offer has been cancelled successfully"]);
        $this->emit('reRenderParent');
        $this->closeModal();
        return redirect()->route("offers");
    }

    public function cancelSellCertificateById($sellCertificateId){
        $sellCertificate = SellCertificate::find($sellCertificateId);
        $sellCertificate->cancelSellCertificate($sellCertificate);
    }

    public function sendEmailToBuyer($email, $certificateName) {
        $data['title'] = $certificateName. " Offer is cancelled";
        $data['body'] = "Owner cancelled sell certificate so your offer was cancelled";
        Mail::to($email)->send(new BidVerifyMail($data));
    }

    public function cancelRelativeOffers($sellCertificateId){
        $allBids = Bid::where(function($query) {
                $query->where('status',0)
                    ->orWhere('status',3);
            })
            ->where('sell_certificate_id', $sellCertificateId)
            ->get();

        foreach ($allBids as $allBid) {
            $email = $allBid->user->email;
            if ($allBid->counterOffer && $allBid->counterOffer->count() > 0) {
                $currentCounterOffer = CounterOffer::where('bid_id', $allBid->id)->latest()->first();

                if ($currentCounterOffer->status == 0 || $currentCounterOffer->status == 3) {
                    $currentCounterOffer->status = 4;
                    $currentCounterOffer->status_update_user_id = Auth::id();
                    $currentCounterOffer->save();

                    //send mail to user
//                    $this->sendEmailToBuyer($email, $allBid->certificate->name);
                    $msg = 'The seller cancelled the sell for this carbon credit <a href="'.route('buy.show.certificate',$allBid->sell_certificate->id).'">'.$allBid->certificate->name . '</a> . There is no option to proceed with this deal';
                    $allBid->user->notify(new SendMessageNotification($msg,'offers'));
                }
            } else {
                $allBid->status = 4;
                $allBid->save();

                //send mail to user
//                $this->sendEmailToBuyer($email, $allBid->certificate->name);
                $msg = 'The seller cancelled the sell for this carbon credit <a href="'.route('buy.show.certificate',$allBid->sell_certificate->id).'">'.$allBid->certificate->name . '</a>. There is no option to proceed with this deal';
                $allBid->user->notify(new SendMessageNotification($msg,'offers'));
            }
        }
    }
}
