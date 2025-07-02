<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Bid;
use Livewire\Component;

class Bids extends Component
{
    public function render()
    {
        $this->user     =   auth()->user();
        $certificates = Bid::where('user_id',$this->user->id)->orderBy('id','desc')->get();

        foreach($certificates as &$certificate){
            $difference = ($certificate->rate * 100) /$certificate->sell_certificate->price_per_unit;
            $certificate->priceDifference = $difference;
            $certificate->differenceType = $certificate->rate > $certificate->sell_certificate->price ? 'inc' : 'dec';
        }

        return view('livewire.dashboard.bids',compact('certificates'));
    }
}
