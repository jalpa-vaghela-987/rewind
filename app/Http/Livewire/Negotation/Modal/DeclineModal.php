<?php

namespace App\Http\Livewire\Negotation\Modal;

use App\Mail\BidVerifyMail;
use App\Models\Bid;
use App\Models\CounterOffer;
use App\Notifications\SendMessageNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class DeclineModal extends Component
{
    protected $listeners = ['reRenderParent'=>'reRenderComponent'];
    public $declineModal = false;
    public $selectedBid = null;
    public $counterOfferId = null;
    public $isDisabled = false;
    public function render()
    {
        return view('livewire.negotation.modal.decline-modal');
    }

    public function mount()
    {
        $this->declineModal = !$this->declineModal;
    }

    public function closeModal()
    {
        $this->declineModal = false;
        //*******TEMPORARY ADD REFRESH ALL***********
        $this->emit('refreshAll');
    }

    public function reRenderComponent(){
        $this->mount();
        $this->render();
    }

    public function declineOffer() {
        $this->isDisabled = true;
        $status = 2;
        $bid = $this->selectedBid;
        $counterOfferId =   $this->counterOfferId;

        if($counterOfferId && $counterOfferId != null) {
            $email = (Auth::id() != $bid->user_id) ? $bid->user->email : $bid->certificate->user->email;

            CounterOffer::where('id', $counterOfferId)->update([
                'status' => $status,
                'status_update_user_id' => Auth::id()
            ]);
        } else {
            $email = $bid->user->email;
            $bid->status = $status;
            $bid->save();
        }
        $msg='The seller declined your bid for this carbon credit: <a href="'.route('buy.show.certificate',$bid->sell_certificate->id).'">'.$bid->certificate->name. '</a>';
        $bid->user->notify(new SendMessageNotification($msg,'offers'));

//        $this->sendEmail($email, $bid->certificate->name);
        $this->emitTo('flash-component', 'flashMessage',['type' => 'success', 'msg' => "Your offer has been decline successfully"]);
        $this->emit('reRenderParent');
        $this->closeModal();
        return redirect()->route("offers");
    }

    public function sendEmail($email, $certificateName) {
        $data['title'] = $certificateName. " Offer is declined";
        $data['body'] = "Owner decline your offer successfully";

        //*******TEMPORARY COMMENT***********
        //Mail::to($email)->send(new BidVerifyMail($data));
    }
}
