<?php

namespace App\Http\Livewire\Profile\Payment;

use App\Models\BankDetail;
use App\Models\CardDetail;
use App\Models\User;
use Livewire\Component;

class ManagePayment extends Component
{
    public User $user;
    public $cards;
    public $banks;
    public $primary_card_id;
    public $primary_bank_id;
    protected $listeners = [
        "refresh"=>'$refresh',
        'reRenderComponent'=>'reRenderComponent'
    ];
    public function render()
    {
        return view('livewire.profile.payment.manage-payment');
    }
    public function rules(){
        return [
            'primary_card_id'=>'required|integer|exists:card_details,id,user_id,'.auth()->user()->id,
            'primary_bank_id'=>'required|integer|exists:bank_details,id,user_id,'.auth()->user()->id
        ];
    }
    public function messages(){
        return [
            'primary_card_id.required'=>'Card ID is required',
            'primary_card_id.integer'=>'Card ID must be an integer',
            'primary_card_id.exists'=>'Card ID is invalid',
            'primary_bank_id.required'=>'Bank ID is required',
            'primary_bank_id.integer'=>'Bank ID must be an integer',
            'primary_bank_id.exists'=>'Bank ID is invalid'
        ];
    }
    public function mount(){
        $this->user     =   auth()->user();
        if($this->user->creditCards){
            $this->primary_card_id = $this->user->creditCard?$this->user->creditCard->id:null;//creditCard for primary bank
            $this->cards  =   $this->user->creditCards;
        }
        if($this->user->bankAccounts){
            $this->primary_bank_id = $this->user->bankAccount?$this->user->bankAccount->id:null; //bankAccount for primary bank
            $this->banks  =   $this->user->bankAccounts;
        }
    }
    public function updatedPrimaryCardId($value){
        $type   =   'error';
        $msg    =   'Technical error!';
        $this->primary_card_id     =   $value;
        $this->validateOnly('primary_card_id');
        $udpate  =  CardDetail::where('user_id',$this->user->id)->update(['is_primary'=>0]);
        $card    =  CardDetail::find($this->primary_card_id);
        $card->is_primary  = true;
        if($card->save()){
            $type = 'success';
            $msg = 'Card with last four digits:'.substr($card->card_no,'-4').' is set to primary!';
            $msg1 = 'Card with last four <b>digits:'.substr($card->card_no,'-4').'</b> is set to primary!';
            activity()
            ->performedOn($card)
            ->causedBy(auth()->user())
            ->log($msg1);
        }
        $this->emitTo('flash-component', 'flashMessage',['type' => $type, 'msg' => $msg]);
        $this->emit('reRenderComponent');
    }
    public function updatedPrimaryBankId($value){
        $type   =   'error';
        $msg    =   'Technical error!';
        $this->primary_bank_id     =   $value;
        $this->validateOnly('primary_bank_id');
        $udpate  =  BankDetail::where('user_id',$this->user->id)->update(['is_primary'=>0]);
        $bank    =  BankDetail::find($this->primary_bank_id);
        $bank->is_primary  = true;
        if($bank->save()){
            $type = 'success';
            $msg = 'Bank account is set to primary!';
            $msg1 = 'Bank account with <b>IBAN: :subject.iban</b> is set to primary.';
            activity()
            ->performedOn($bank)
            ->causedBy(auth()->user())
            ->log($msg1);
        }
        $this->emitTo('flash-component', 'flashMessage',['type' => $type, 'msg' => $msg]);
        $this->emit('reRenderComponent');
    }
    public function reRenderComponent(){
        $this->mount();
        $this->render();
    }
}
