<?php

namespace App\Http\Livewire\Negotation;

use App\Models\Bid;
use App\Models\Certificate;
use App\Models\CounterOffer;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $listeners    =   ['getStatus', 'openCloseOfferedModal', 'refreshAll'];
    public $offeredModal = false;
    public $selectedBid = null;
    public $price = null;
    public $quantity = null;
    public $counterOfferId = null;
    public $status = null;

    public function render()
    {
        $user = Auth::user();
        $certificates = Certificate::paginate(10);
        $bids = Bid::with('sell_certificate')
            ->WhereHas('sell_certificate',function($query) use ($user){
                $query->where('user_id', $user->id);
            })
            ->orWhere('user_id', $user->id)
            ->orderBy('created_at','DESC')
            ->get(10);
        return view('livewire.negotation.index', compact('bids', 'certificates'));
    }

    public function refreshAll(){
        return redirect()->route("offers");
    }

    public function getStatus($bidId)
    {
        $status = $this->status;
        $this->getSelectedBid($bidId);
        //set counter offer ID
        $counterOfferId =   CounterOffer::where('bid_id',$bidId)->latest()->value('id');
        if ($counterOfferId != 0) {
            $this->counterOfferId = $counterOfferId;
        }
    }

    public function openCloseOfferedModal()
    {
        $this->offeredModal = !$this->offeredModal;
    }

    public function getSelectedBid($bidId)
    {
        $bid = Bid::find($bidId);
        $this->selectedBid = $bid;
        if($this->selectedBid->counterOffer && $this->selectedBid->counterOffer->count() > 0){
            $this->selectedBid->currentCounterOffer = CounterOffer::where('bid_id',$bidId)->latest()->first();
        }
    }
}
