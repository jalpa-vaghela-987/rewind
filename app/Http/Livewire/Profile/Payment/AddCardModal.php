<?php

namespace App\Http\Livewire\Profile\Payment;

use App\Models\CardDetail;
use App\Models\User;
use App\Notifications\SendMessageNotification;
use Livewire\Component;

class AddCardModal extends Component
{
    protected $listeners    =   [
                                    "openCloseAddCardModal"=>"openCloseAddCardModal",
                                    "refresh"=>'$refresh'
                                ];
    public $showModal   =   false;
    public $expiry;
    public $card_no, $card_holder_name, $expiry_month, $expiry_year, $cvv;
    public $card;
    public $heading, $is_primary=0;
    public function rules(){
        $expiry_month   =   date('m');
        $expiry_year    =   date('Y');

        if(isset($this->expiry_year)){
            $expiry_year    =   $this->expiry_year;
        }
        if(isset($this->expiry_month)){
            $expiry_month    =   $this->expiry_month;
        }
        $this->expiry   =   $expiry_month.'/31/'.$expiry_year;
        // $this->expiry_month = (int)$this->expiry_month;
        return [
            'card_no'           => ['required', $this->card?'unique:card_details,card_no,'.$this->card->id:'unique:card_details,card_no,NULL,id,user_id,'.auth()->user()->id,'digits:16'],
            'card_holder_name'  => 'nullable',
            'expiry_month'      => ['required','numeric','min:01','max:12', 'digits:2'],
            'expiry_year'       => ['required','numeric','digits:4','max:2099'],
            'cvv'               => ['required','digits:3'],
            'expiry'            => 'after:'.date('m/d/Y'),
        ];
    }
    public function messages(){
        return [
            'expiry_month.integer'  =>  'Month should be valid month.',
            'expiry_month.min'      =>  'Month should be valid month.',
            'expiry_month.max'      =>  'Month should be valid month.',
            'expiry_year.integer'   =>  'Year must be a valid year',
            'expiry_year.digits'    =>  'Year must be between '.date('Y').'-2099',
            'expiry_year.max'       =>  'Year must be between '.date('Y').'-2099',
            'expiry.after'          =>  'Expiry month/year is invalid'
        ];
    }
    public function render()
    {
        return view('livewire.profile.payment.add-card-modal');
    }
    public function mount()
    {
        if($this->card){
            $this->card_no          =   $this->card->card_no;
            $this->card_holder_name =   $this->card->card_holder_name;
            $this->expiry_month     =   $this->card->expiry_month;
            $this->expiry_year      =   $this->card->expiry_year;
            $this->cvv              =   $this->card->cvv;
            $this->is_primary          =   $this->card->is_primary;
            $this->heading          =   "Update Card Details";
        }else{
            $this->heading          =   "Add Card Details";
        }
    }
    public function openCloseAddCardModal($card_id=null){
        $this->clearForm();
        $this->card =  $card_id ? CardDetail::find($card_id) : Null;
        $this->resetValidation();
        $this->mount();
        $this->showModal    =   !$this->showModal;
    }
    public function updated($prop){
        if(isset($this->card->$prop)){
            $this->card->$prop  =   $this->$prop;
        }
    }
    public function save(){
        $this->validate();
        if(!auth()->user()->creditCard){
            $this->is_primary = true;
        }
        if($this->card){
            $update =   CardDetail::where('user_id',auth()->user()->id)
                        ->where('id',$this->card->id)
                        ->update([
                            'card_no'=>$this->card_no,
                            'card_holder_name'=>$this->card_holder_name,
                            'expiry_month'=>$this->expiry_month,
                            'expiry_year'=>$this->expiry_year,
                            'cvv'=>$this->cvv,
                            'is_primary'=>$this->is_primary,
                        ]);
            $saved = $this->card;
            $message='Card details has been updated';
            $msg = 'Credit Card Detail Changed by:<a href="'.route('admin.users').'">'.  auth()->user()->name. '</a>';
            $user = User::find(1);
            $user->notify(new SendMessageNotification($msg));
        }else{
            $saved = CardDetail::create([
                'card_no'=>$this->card_no,
                'card_holder_name'=>$this->card_holder_name,
                'expiry_month'=>$this->expiry_month,
                'expiry_year'=>$this->expiry_year,
                'cvv'=>$this->cvv,
                'user_id'=>auth()->user()->id,
                'is_primary'=>$this->is_primary,
            ]);
            $message='Card details has been saved';
            $msg = 'New Credit Card Detail Added by:<a href="'.route('admin.users').'">'.  auth()->user()->name. '</a>';
            $user = User::find(1);
            $user->notify(new SendMessageNotification($msg));
        }
        $type = 'success';
        $msg = 'Card Details Saved Successfully!';
        $this->emitTo('flash-component', 'flashMessage',['type' => $type, 'msg' => $msg]);
        activity()
        ->performedOn($saved)
        ->causedBy(auth()->user())
        ->log($message);
        $this->reset();
        $this->emit('reRenderComponent');
    }

    public function reRenderComponent(){
        $this->mount();
        $this->render();
    }

    public function clearForm()
    {
        $this->card_no="";
        $this->card_holder_name="";
        $this->expiry_month="";
        $this->expiry_year="";
        $this->cvv="";
    }
}
