<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use Livewire\Component;

class VerifyEmail extends Component
{
    public $validate_str;
    public function render()
    {
        $layout = auth()->check()?'layouts.app':'layouts.guest';
        return view('livewire.auth.verify-email')->layout($layout);
    }
    public function mount(){
        $user =   User::where('email_verification_string',$this->validate_str)->where('email_verified',false)->first();
        if ($user) {
            if ($user->new_email) {
                $user->email = $user->new_email;
                $user->new_email = null;
            }
            $user->email_verification_string = null;
            $user->email_verified = true;
            $user->save();
            activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->log('email:<b>:subject.email</b> has been verified successfully');
        }else{
            $type   =   "warning";
            $msg    =   "You're using wrong url";
            $this->emitTo('flash-component', 'flashMessage',['type' => $type, 'msg' => $msg]);
            return redirect()->route('login');
        }
    }
}
