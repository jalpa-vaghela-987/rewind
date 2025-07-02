<?php

namespace App\Http\Livewire\Profile\Payment;

use App\Models\BankDetail;
use App\Models\Country;
use App\Models\User;
use App\Notifications\SendMessageNotification;
use Faicchia\IbanValidation\Rules\Iban;
use Livewire\Component;

class BankFormModal extends Component
{
    protected $listeners    =   [
        "openCloseBankFormModal"=>"openCloseBankFormModal",
        "refresh"=>'$refresh'
    ];
    public $showModal   =   false;
    public $beneficiary_name;
    public $bic;
    public $iban;
    public $bank_name;
    public $country_id;
    public $bank;
    public $heading;
    public $countries,$is_primary=0;
    public function rules(){
        return [
            'bank_name'         => 'required',
            'iban'              => ['required',new Iban(),$this->bank?'unique:bank_details,iban,'.$this->bank->id:'unique:bank_details,iban','min:22','max:34'],
            'beneficiary_name'  => 'nullable',
            'bic'               => ['required','max:11'],
            'country_id'        => ['required','exists:countries,id'],
        ];
    }
    public function messages(){
        return [
        ];
    }
    public function render()
    {
        $this->countries    =   Country::select("name","id")->where('is_active',1)->get();
        return view('livewire.profile.payment.bank-form-modal');
    }
    public function mount(){
        if($this->bank){
            $this->bank_name        =   $this->bank->name;
            $this->iban             =   $this->bank->iban;
            $this->beneficiary_name =   $this->bank->beneficiary_name;
            $this->bic              =   $this->bank->bic;
            $this->country_id       =   $this->bank->country_id;
            $this->is_primary          =   $this->bank->is_primary;
            $this->heading          =   "Update Your Bank Account Details";
        }else{
            $this->heading          =   "Enter Your Bank Account Details";
        }
    }
    public function openCloseBankFormModal($bank_id=null){
        $this->clearForm();
        $this->bank = $bank_id? BankDetail::find($bank_id) : Null;
        $this->resetValidation();
        $this->mount();
        $this->showModal    =   !$this->showModal;
    }
    public function save(){
    $this->validate();
    if(!auth()->user()->bankAccount){
        $this->is_primary = true;
    }
    if($this->bank){
        BankDetail::where('user_id',auth()->user()->id)
        ->where('id',$this->bank->id)
        ->update([
            'name'=>$this->bank_name,
            'iban'=>$this->iban,
            'beneficiary_name'=>$this->beneficiary_name,
            'bic'=>$this->bic,
            'country_id'=>$this->country_id,
            'is_primary'=>$this->is_primary,
        ]);
        $saved = BankDetail::where('user_id',auth()->user()->id)->first();
        $message = 'bank details with <br>IBAN :subject.iban </br>has been updated';
        $msg = 'Bank Detail Changed by:<a href="'.route('admin.users').'">'.  auth()->user()->name. '</a>';
        $user = User::find(1);
        $user->notify(new SendMessageNotification($msg));
    }else{
        $saved = BankDetail::create([
            'name'=>$this->bank_name,
            'iban'=>$this->iban,
            'beneficiary_name'=>$this->beneficiary_name,
            'bic'=>$this->bic,
            'country_id'=>$this->country_id,
            'user_id'=>auth()->user()->id,
            'is_primary'=>$this->is_primary,
        ]);
        $this->emit('addBankForSell', $saved);
        $message = 'bank details with <br>IBAN :subject.iban </br>has been Saved';
        $msg = 'Bank Detail Added by:<a href="'.route('admin.users').'">'.  auth()->user()->name. '</a>';
        $user = User::find(1);
        $user->notify(new SendMessageNotification($msg));
    }
    $type = 'success';
    $msg = 'Bank Details Saved Successfully!';
    $this->emitTo('flash-component', 'flashMessage',['type' => $type, 'msg' => $msg]);
    $this->openCloseBankFormModal();
    activity()
        ->performedOn($saved)
        ->causedBy(auth()->user())
        ->log($message);
    $this->reset();
    $this->emit('reRenderComponent');
    $this->emitSelf('refresh');

    }
    public function clearForm()
    {
        $this->bank_name="";
        $this->iban="";
        $this->beneficiary_name="";
        $this->bic="";
        $this->country_id="";
    }
}
