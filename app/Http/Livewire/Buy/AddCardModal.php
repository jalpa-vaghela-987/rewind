<?php

namespace App\Http\Livewire\Buy;

use App\Models\CardDetail;
use Livewire\Component;

class AddCardModal extends Component
{
    protected $listeners = ['openCreditCardModal'];
    public $showModal  =   false;
    public $expiry, $card_no, $card_holder_name, $expiry_month, $expiry_year, $cvv, $afterYear;
    public function rules()
    {
        $expiry_month = date('m');
        $expiry_year = date('Y');
        $this->afterYear=date("Y")-1; //date ("Y", strtotime ("-1 year", strtotime (date('Y'))))

        if (isset($this->expiry_year)) {
            $expiry_year = $this->expiry_year;
        }
        if (isset($this->expiry_month)) {
            $expiry_month = $this->expiry_month;
        }
        $this->expiry = $expiry_month . '/31/' . $expiry_year;

        return [
            'card_no' => ['required', 'unique:card_details,card_no,NULL,id,user_id,'.auth()->user()->id, 'digits:16'],
            'card_holder_name' => ['nullable','regex:/^[a-zA-Z\s]+$/'],
            'expiry_month' => ['required', 'numeric', 'min:01', 'max:12', 'digits:2'],
            'expiry_year'       => ['required','numeric','date_format:Y','max:2099','after:'.$this->afterYear],
            'cvv' => ['required', 'digits:3'],
            'expiry' => 'after:' . date('m/d/Y'),
        ];
    }

    public function messages()
    {
        return [
            'expiry_month.integer' => 'Month should be valid month.',
            'expiry_month.min' => 'Month should be valid month.',
            'expiry_month.max' => 'Month should be valid month.',
            'expiry_year.integer' => 'Year must be a valid year',
            'expiry_year.date_format' =>  'Year must in format YYYY',
            'expiry_year.after'     =>  'Year must be after '.$this->afterYear,
            'expiry_year.max' => 'Year must be between ' . date('Y') . '-2099',
            'expiry.after' => 'Expiry month/year is invalid',
            'card_holder_name.regex'=>'Special Characters are not allowed.'
        ];
    }
    public function render()
    {
        return view('livewire.buy.add-card-modal');
    }
    public function openCreditCardModal(){
        $this->showModal    =   true;
    }
    public function closeModal(){
        $this->showModal    =   false;
    }
    /**
     * @author Moh Ashraf
     */
    public function saveNewCard()
    {
        $this->validate();
        $buyer = auth()->user();
        $cardDetails = [
            'user_id' => $buyer->id,
            'card_no' => $this->card_no,
            'card_holder_name' => $this->card_holder_name,
            'expiry_month' => $this->expiry_month,
            'expiry_year' => $this->expiry_year,
            'cvv' => $this->cvv,
            'is_active' => 1,
            'is_primary' => 1
        ];
        CardDetail::where('user_id', $buyer->id)->update([
            'is_primary' => 0
        ]);
        $card = CardDetail::create($cardDetails);
        $type = 'success';
        $msg = 'Card Added for payment!';
        $this->emit('reRenderParent');
        $this->emitTo('flash-component', 'flashMessage', ['type' => $type, 'msg' => $msg]);
        $this->closeModal();
    }
}
