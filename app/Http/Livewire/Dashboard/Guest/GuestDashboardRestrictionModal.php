<?php

namespace App\Http\Livewire\Dashboard\Guest;

use Livewire\Component;

class GuestDashboardRestrictionModal extends Component
{
    protected $listeners    =   [
        "openCloseRestrictionModal"=>"openCloseRestrictionModal",
    ];
    public $showLoginModal   =   false;

    public function render()
    {
        return view('livewire.dashboard.guest.guest-dashboard-restriction-modal');
    }

    public function openCloseRestrictionModal(){
        $this->showLoginModal    =   !$this->showLoginModal;
    }
}
