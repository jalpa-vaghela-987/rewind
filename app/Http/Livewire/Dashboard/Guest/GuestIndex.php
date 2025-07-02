<?php

namespace App\Http\Livewire\Dashboard\Guest;

use Livewire\Component;

class GuestIndex extends Component
{
    public function render()
    {
        return view('livewire.dashboard.guest.guest-index')->layout('layouts.guest-dashboard');
    }
}
