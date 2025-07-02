<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class VerifyForgotPasswordModal extends Component
{
    public $showModal = false, $token,  $new_password, $confirm_password;

    public function render()
    {
        return view('livewire.auth.verify-forgot-password-modal');
    }

    public function mount($showModal, $token)
    {
        $this->showModal = $showModal;
        $this->token = $token;
    }

    public function openCloseVerifyForgotPasswordModal(){
        $this->showModal    =   !$this->showModal;
        return redirect()->route('login');
    }

    public function forgotPassword()
    {
        $this->validate([
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);

        $userToken = DB::table('user_tokens');
        $userTokenData = $userToken->where('token', $this->token)->first();

        if (!empty($userTokenData))
        {
            $checkToken = $userToken->where('created_at','>',Carbon::now()->subHours(2))->first();
            if (!empty($checkToken))
            {
                $user = User::find($checkToken->user_id);
                if (!Hash::check($this->new_password, $user->password))
                {
                    User::where('id', $checkToken->user_id)->update([
                        'password' => Hash::make($this->confirm_password)
                    ]);
                    $this->showModal = false;
                    return redirect()->route('login')->with('changedPassword', true);
                }
                else
                {
                    session()->flash('error', 'Please enter new password');
                    $this->render();
                }
            }
            else
            {
                session()->flash('error', 'Your token is expire, please send again email');
                $this->render();
            }
        } else {
            session()->flash('error', 'Invalid user');
            $this->render();
        }
    }
}
