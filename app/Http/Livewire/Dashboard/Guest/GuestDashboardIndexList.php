<?php

namespace App\Http\Livewire\Dashboard\Guest;

use App\Models\Subscription;
use Livewire\Component;

class GuestDashboardIndexList extends Component
{
    public function render()
    {
        $certificates = Subscription::select('*')->with('certificate')->orderBy('id','desc')->get()->groupBy('certificate_id');
        foreach($certificates as $certificate){
            $difference = ($certificate[0]->price * 100) /$certificate[0]->certificate->price;
            $difference = abs(100 - $difference);
            $certificate[0]->priceDifference = $difference;
            $certificate[0]->differenceType = $certificate[0]->price > $certificate[0]->certificate->price ? 'inc' : 'dec';
        }
        return view('livewire.dashboard.guest.guest-dashboard-index-list', compact('certificates'));
    }
}
