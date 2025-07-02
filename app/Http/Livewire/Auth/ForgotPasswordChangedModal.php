<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;

class ForgotPasswordChangedModal extends Component
{
    public $showModal = false;

    public function render()
    {
        return view('livewire.auth.forgot-password-changed-modal');
    }

    public function openCloseForgotPasswordChangedModal()
    {
        $this->showModal = !$this->showModal;
        return redirect()->route('login');
    }
}
