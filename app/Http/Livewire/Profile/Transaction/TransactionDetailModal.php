<?php

namespace App\Http\Livewire\Profile\Transaction;

use App\Models\Subscription;
use Livewire\Component;

class TransactionDetailModal extends Component
{
    public $showModal       =   false;
    protected $listeners    =   ['openTransactionDetailModal'=>'openTransactionDetailModal'];
    public $transaction;
    public function render()
    {
        return view('livewire.profile.transaction.transaction-detail-modal');
    }
    public function closeTransactionDetailModal(){
        $this->showModal    =   false;
    }
    public function mount(){

    }
    public function openTransactionDetailModal($transaction_id){
        $transaction_id =   base64_decode($transaction_id);
        $transaction    =   Subscription::find($transaction_id);
        if($transaction){
            $this->transaction  =   $transaction;
            $this->showModal = true;
        }else{
            session()->flash("error","Transaction details not found!");
        }
    }
}
