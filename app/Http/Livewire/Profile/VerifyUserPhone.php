<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;
use App\Models\User;
use App\Models\UserToken;

class VerifyUserPhone extends Component
{
    public $validate_str;
    public function render(){
        $layout = auth()->check()?'layouts.app':'layouts.guest';
        return view('livewire.profile.verify-user-phone')->layout($layout);
    }
    public function mount(){
        $update   =   User::where('phone_verification_string',$this->validate_str)->update(["phone_verification_string"=>null,"phone_verified"=>true]);
        $deleteToken    =   UserToken::where('user_id',auth()->user()->id)
                                    ->where('type','phone')
                                    ->delete();
        if(!$update){
            return redirect()->route('login');
        }
    }
}
