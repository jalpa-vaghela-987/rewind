<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Livewire\Component;

class Logout extends Component
{
    protected $listeners = ["openCloseLogoutModal"];
    public $showModal   =   false;
    public User $user;
    public function render()
    {
        return view('livewire.auth.logout');
    }
    public function logoutUser(){
        $this->user     =   auth()->user();
        Auth::guard()->logout();
        activity()
            ->performedOn($this->user)
            ->causedBy($this->user)
            ->log('Logout successfully');
        session()->flash('success', 'Logout Successfully');
        $redirectUrl    =   route('login');
        return redirect($redirectUrl);
    }
    public function mount(){
        // $this->user = auth()->user();
        // Auth::guard()->logout();
        // activity()
        //     ->performedOn($this->user)
        //     ->causedBy($this->user)
        //     ->log('Logout successfully');
        // session()->flash('success', 'Logout Successfully');
        // $redirectUrl    =   route('login');
        // return redirect($redirectUrl);
    }
    public function openCloseLogoutModal(){
        $this->showModal = !$this->showModal;
    }
}
