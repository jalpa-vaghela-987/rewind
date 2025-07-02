<?php

namespace App\Http\Livewire\Buy;

use App\Models\BankDetail;
use App\Models\Bid;
use App\Models\CardDetail;
use App\Models\SellCertificate;
use App\Models\Subscription;
use Livewire\Component;

class StripeModal extends Component
{
    public function render()
    {
        return view('livewire.buy.stripe-modal');
    }
    
    protected $listeners = ['openStripeModal','setPrimaryCardId','reRenderParent'];
    public $showModal  =   false;
    public $user, $cards, $primary_card_id;
    public function rules(){
        return [
            'primary_card_id' => 'required|integer|exists:card_details,id',
        ];
    }

    public function messages(){
        return [
            'primary_card_id.required'=>'Please select credit card',
            'primary_card_id.integer'=>'Credit card ID must be an integer',
            'primary_card_id.exists'=>'Selected credit card is invalid',
        ];
    }
    public function mount(){
        $this->resetValidation();
        $this->user             =   auth()->user();
        $this->cards            =   $this->user->creditCards;
        $this->primary_card_id  =   $this->user->creditCard?$this->user->creditCard->id:0;
    }
    public function openStripeModal(){
        $this->showModal    =   true;
    }
    public function closeModal(){
        $this->mount();
        $this->showModal    =   false;
    }
    /**
     * @author Moh Ashraf
     */
    public function showAddCreditCardmodal()
    {
        $this->resetValidation();
        $this->emit('openCreditCardModal');
    }
    public function setPrimaryCardId($primary_card_id){
        $this->primary_card_id  =   $primary_card_id;
    }
    public function reRenderParent(){
        $this->render();
        $this->mount();
    }
    public function buy(){
        if($this->primary_card_id=='card_id'){
            $this->primary_card_id  =   0;
        }
        $this->validate();
        if ($this->primary_card_id != data_get($this->user, 'creditCard.id')) {
            $cardDetail = CardDetail::firstWhere(['user_id' => $this->user->id, 'id' => $this->primary_card_id]);
            if ($cardDetail) {
                $removePrimary = CardDetail::where('user_id', $this->user->id)->update([
                    'is_primary' => 0
                ]);
                $cardDetail->is_primary = true;
                $cardDetail->save();
            } else {
                $message = 'Invalid Card!';
                $this->closeModal();
                $this->clear();
                $this->emitTo('flash-component', 'flashMessage', ['type' => 'error', 'msg' => $message]);
            }
        }
        $this->emit('finalBuy');
        $this->closeModal();
    }
}
