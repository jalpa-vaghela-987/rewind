<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Subscription;
use Livewire\Component;

class LatestSale extends Component
{
    public function render()
    {
        $this->user     =   auth()->user();
        $certificates = Subscription::where('receiver_id',$this->user->id)->orderBy('id','desc')->take(5)->get();
        return view('livewire.dashboard.latest-sale',compact('certificates'));
    }
}
