<?php

namespace App\Http\Livewire\Auth;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Laravel\Socialite\Facades\Socialite;
class SocialLogin extends Component
{
    public $type="google";
    public function mount(){
        $socialUser  =   Socialite::driver($this->type)->user();
        $user       =   User::where($this->type.'_id',$socialUser->id)->first();
        if(!$user){
            $user                       =   new User();
            $user->name                 =   $socialUser->name;
            $user->email                =   $socialUser->email;
            $user->status               =   1;
            $user->profile_photo_path   =   $socialUser->avatar;
            $user->email_verified       =   true;
            $user->save();
        }
        if(!$user->hasRole('user') ){
            $user->assignRole(ROLE_USER);
        }
        if($this->type=='azure'){
            $socialData = [
                'user_id'=>$user->id,
                'provider_type' => User::TYPE_MICROSOFT,
                'provider_token' => $socialUser->token,
                'refresh_token' => $socialUser->refreshToken
            ];
        }else{
            $socialData = [
                'user_id'=>$user->id,
                'provider_type' => $this->type,
            ];
        }
        $socialAccount  =   SocialAccount::updateOrCreate([
            'provider_id' => $socialUser->id
        ], $socialData);
        Auth::login($user);
        session()->flash('success', 'You have been successfully login.');
        activity()
        ->performedOn($user)
        ->causedBy(auth()->user())
        // ->withProperties(['laravel' => 'awesome'])
        ->log('logged in as <b>:subject.name</b> from <b>:subject.account_type</b> account');

        return redirect()->route('dashboard');
    }
}
