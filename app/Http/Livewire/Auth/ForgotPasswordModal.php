<?php

namespace App\Http\Livewire\Auth;

use App\Mail\ForgotPasswordLink;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;

class ForgotPasswordModal extends Component
{
    protected $listeners = ['openCloseForgotPasswordModal'];
    public $showModal = false;
    public $email, $token;
    public $success = null;
    public $error = null;

    public function render()
    {
        return view('livewire.auth.forgot-password-modal');
    }

    public function openCloseForgotPasswordModal(){
        $this->clear();
        $this->showModal    =   !$this->showModal;
    }

    public function sendResetPasswordLink()
    {
        $this->clear();
        $this->validate([
            'email' => 'required|email|exists:users,email'
        ]);
        $user           =   User::where('email',$this->email)->first();
        $this->token    =   Str::random(64);
        DB::table('user_tokens')->updateOrInsert(
            [
                'user_id' => $user->id
            ],
            [
            'token' => $this->token,
            'created_at' => Carbon::now(),
            'type' => 'forgot_password'
        ]);

        $userToken = DB::table('user_tokens')->where('token', $this->token)->first();
        try {
            Mail::to($user->email)->send(new ForgotPasswordLink($userToken));
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return 1;
        }
        $this->success = 'Reset password link has been send your email successfully';
        $this->email = null;
    }

    public  function clear()
    {
        $this->success = null;
        $this->error = null;
    }
}
