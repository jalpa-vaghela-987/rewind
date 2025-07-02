<?php

namespace App\Http\Livewire\Profile;

use App\Models\User;
use Livewire\Component;

class Profile extends Component
{
    protected $listeners = [
        "refresh"=>'$refresh',
        'reRenderParent'=>'reRenderParent',
        'changeTab'
    ];
    public User $user;
    public $activeTab = "details";

    public function render()
    {
        $this->user =   auth()->user();
        return view('livewire.profile.profile');
    }

    public function mount($tab){
        $this->activeTab = $tab;
    }

    public function reRenderParent(){
        $this->mount($this->activeTab);
        $this->render();
    }
}
