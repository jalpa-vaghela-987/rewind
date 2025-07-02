<?php

namespace App\Http\Livewire\Negotation\Modal;

use App\Mail\BidVerifyMail;
use App\Models\Bid;
use App\Models\Certificate;
use App\Models\CounterOffer;
use App\Models\SellCertificate;
use App\Models\Subscription;
use App\Notifications\SendMessageNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class AcceptModal extends Component
{
    protected $listeners = ['reRenderParent' => 'reRenderComponent'];
    public $acceptModal = false;
    public $selectedBid = null;
    public $counterOfferId = null;
    public $isDisabled = false;

    public function render()
    {
        return view('livewire.negotation.modal.accept-modal');
    }

    public function mount()
    {
        $this->acceptModal = !$this->acceptModal;
    }

    public function closeModal()
    {
        $this->acceptModal = false;
        //*******TEMPORARY ADD REFRESH ALL***********
        $this->emit('refreshAll');
    }

    public function reRenderComponent(){
        $this->mount();
        $this->render();
    }

    public function acceptOffer() {
        $this->isDisabled = true;
        $status = 1;
        $bid = $this->selectedBid;
        $certificate = $bid->certificate;
        $sell_certificate = $bid->sell_certificate;
        $receiver  = $certificate->user;
        $counterOfferId =   $this->counterOfferId;

        if($counterOfferId && $counterOfferId != null) {
            $counterOffer = CounterOffer::find($counterOfferId);
            $offerAmount = $counterOffer->amount;
            $offerQuantity = $counterOffer->quantity;
        } else {
            $offerAmount = $bid->amount;
            $offerQuantity = $bid->unit;
        }

        if ($sell_certificate->remaining_units > 0 && $sell_certificate->remaining_units >= $offerQuantity) {
            Subscription::create([
                'user_id' => $bid->user_id,
                'receiver_id' => $receiver->id,
                'name' => $receiver->name,
                'stripe_id' => $receiver->stripe_id . rand(), //remove
                'stripe_price' => $bid->amount,
                'amount' =>$offerAmount,
                'quantity' => $offerQuantity,
                'stripe_status' => 'success',
                'certificate_id' => $certificate->id,
                'sell_certificate_id' => $sell_certificate->id,
                'card_detail_id' => $bid->card_detail_id,
                'seller_bank_id' => $receiver->bankAccount->id,
                'ip_address' => $bid->ip_address,
            ]);
            $sell_certificate->remaining_units = $sell_certificate->remaining_units - $offerQuantity;
            $sell_certificate->save();
        }

        $this->createPostCertificates($sell_certificate, $offerAmount, $offerQuantity, $bid->user_id);

        if($counterOfferId && $counterOfferId != null) {
            CounterOffer::where('id', $counterOfferId)->update([
                'status' => $status,
                'status_update_user_id' => Auth::id()
            ]);

            $email = (Auth::id() != $bid->user_id) ? $bid->user->email : $bid->certificate->user->email;
        } else {
            $bid->status = $status;
            $bid->save();

            $email = $bid->user->email;
        }

        if($bid->sell_certificate->remaining_units == 0) {
            $this->sendDeclineOfferMailToBuyer($bid);
        }

//        $this->sendEmail($email, $bid->certificate->name);
        if(auth()->user()->id == $bid->user_id){
            $msg='Congratulations! The buyer accepted your counter offer for this carbon credit: <a href="'.route('sell.show.certificate',$bid->sell_certificate->id).'">'.$bid->certificate->name. '</a>';
            $bid->certificate->user->notify(new SendMessageNotification($msg,'offers'));
        }else{
            $msg='Congratulations! The seller accepted your bid for this carbon credit: <a href="'.route('buy.show.certificate',$bid->sell_certificate->id).'">'.$bid->certificate->name. '</a>';
            $bid->user->notify(new SendMessageNotification($msg,'offers'));
        }
        $this->emitTo('flash-component', 'flashMessage',['type' => 'success', 'msg' => "Your offer has been accept successfully"]);
        $this->emit('reRenderParent');
        $this->closeModal();
        return redirect()->route("offers");
    }

    public function createPostCertificates($sellCertificate, $amount, $units, $bidUserId)
    {
        $certificate = Certificate::create([
            'user_id' => $bidUserId,
            'project_type_id' => data_get($sellCertificate->certificate, 'project_type_id'),
            'country_id' => data_get($sellCertificate->certificate, 'country_id'),
            'parent_id' => data_get($sellCertificate->certificate, 'id'),
            'name' => data_get($sellCertificate->certificate, 'name'),
            'quantity' => $units,
            'price' => round($amount, 2),
            'description' => data_get($sellCertificate->certificate, 'description'),
            'approving_body' => data_get($sellCertificate->certificate, 'approving_body'),
            'link_to_certificate' => data_get($sellCertificate->certificate, 'link_to_certificate'),
            'status' => 2
        ]);

        foreach ($sellCertificate->certificate->files()->get() as $file) {
            $certificate->files()->create([
                'file_path' => $file->file_path,
            ]);
        }

        SellCertificate::create([
            'certificate_id' => $certificate->id,
            'user_id' => $bidUserId,
            'units' => $units,
            'remaining_units' => $units,
            'price_per_unit' => round($amount/$units,2),
            'is_main' => true,
            'status' => 2,
        ]);
    }


    public function sendEmailToBuyer($email, $certificateName) {
        $data['title'] = "You need to decline offer ". $certificateName;
        $data['body'] = "Owner had accept other users offer certificate so this certificate quantity is zero, You need to decline this offer";
        //*******TEMPORARY COMMENT***********
        //Mail::to($email)->send(new BidVerifyMail($data));
    }

    public function sendEmail($email, $certificateName) {
        $data['title'] = $certificateName. " Offer is accepted";
        $data['body'] = "Owner accept your offer successfully";

        //*******TEMPORARY COMMENT***********
        //Mail::to($email)->send(new BidVerifyMail($data));
    }

    public  function sendDeclineOfferMailToBuyer($bid){
        $allBids = Bid::where(function($query) {
            $query->where('status',0)
                ->orWhere('status',3);
        })
            ->where('sell_certificate_id', $bid->sell_certificate->id)
            ->where('id', '!=', $bid->id)
            ->get();
        foreach ($allBids as $allBid) {
            $email = $allBid->user->email;
            if ($allBid->counterOffer && $allBid->counterOffer->count() > 0) {
                $currentCounterOffer = CounterOffer::where('bid_id', $allBid->id)->latest()->first();
                if ($currentCounterOffer->status == 0 || $currentCounterOffer->status == 3) {
                    //send mail to user
                    $this->sendEmailToBuyer($email, $allBid->certificate->name);
                }
            } else {
                //send mail to user
                $this->sendEmailToBuyer($email, $allBid->certificate->name);
            }
        }
    }
}
