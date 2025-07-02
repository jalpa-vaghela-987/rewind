<?php

namespace App\Http\Livewire\Negotation\Modal;

use App\Mail\BidVerifyMail;
use App\Models\Bid;
use App\Models\CounterOffer;
use App\Notifications\SendMessageNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class OfferedModal extends Component
{
    protected $listeners = ['reRenderParent'=>'reRenderComponent'];
    public $offeredModal = false;
    public $selectedBid = null;
    public $counterOfferId = null;
    public $price = null;
    public $quantity = null;
    public $isDisabled = false;
    public function render()
    {
        return view('livewire.negotation.modal.offered-modal');
    }

    public function closeModal()
    {
        $this->offeredModal = false;
        $this->emit('openCloseOfferedModal');
        //*******TEMPORARY ADD REFRESH ALL***********
        $this->emit('refreshAll');
    }

    public function reRenderComponent(){
        $this->mount();
        $this->render();
    }

    public function acceptCounterOffer(){
        $status = 3;
        $bid = $this->selectedBid;
        $counterOfferId =   $this->counterOfferId;
        $this->isDisabled = true;

        $existCounterOffer = CounterOffer::where('bid_id', $bid->id)->orderBy('id', 'desc')->first();
        $parentId = ($existCounterOffer) ? $existCounterOffer->parent_id + 1 : 0;

        CounterOffer::create([
            'bid_id' => $bid->id,
            'user_id' => Auth::id(),
            'amount' => $this->price,
            'quantity' => $this->quantity,
            'type' => (auth()->user()->id == $bid->user_id) ? 'buyer' : 'seller',
            'parent_id' => $parentId
        ]);

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

//        $this->sendEmail($email, $bid->certificate->name);
        if(auth()->user()->id == $bid->user_id){
            $msg='The buyer has counter offered to your counter offer for this carbon credit <a href="'.route('sell.show.certificate',$bid->sell_certificate->id).'">'.$bid->certificate->name. '</a>';
            $bid->certificate->user->notify(new SendMessageNotification($msg,'offers'));
        }else{
            $msg='The seller has counter offered to your bid for this carbon credit: <a href="'.route('buy.show.certificate',$bid->sell_certificate->id).'">'. $bid->certificate->name.'</a>';
            $bid->user->notify(new SendMessageNotification($msg,'offers'));
        }
        $this->emitTo('flash-component', 'flashMessage',['type' => 'success', 'msg' => "Your offer has been offered successfully"]);
        $this->emit('reRenderParent');
        $this->closeModal();
        return redirect()->route("offers");
    }

    public function sendEmail($email, $certificateName) {
        $data['title'] = $certificateName. " Counter Offer is approved";
        $data['body'] = "Owner approved your counter offer successfully";

        //*******TEMPORARY COMMENT***********
        //Mail::to($email)->send(new BidVerifyMail($data));
    }
}
