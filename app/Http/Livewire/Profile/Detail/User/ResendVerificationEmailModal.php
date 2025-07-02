<?php

namespace App\Http\Livewire\Profile\Detail\User;

use App\Models\User;
use App\Models\UserToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;

class ResendVerificationEmailModal extends Component
{
    public $showModal       =   false;
    protected $listeners    =   ['openCloseResendVerificationEmail'];
    public $email;
    public function render()
    {
        return view('livewire.profile.detail.user.resend-verification-email-modal');
    }
    public function openCloseResendVerificationEmail(){
        $this->showModal    =   !$this->showModal;
    }
    public function reSendValidationLink(){
        $user = User::where('email',$this->email)->first();
        /**For Mobile */
        $token      =   rand(1000, 9999);
        /**For Mobile */
        $rand_str             =   Str::random(30);
        $verification_url     =   route('verifyEmail',['validate_str'=>$rand_str]);
        $details['title']     =   "Please verify your email";
        $details['url']       =   $verification_url;
        $details['body']    =   'Please click the below link(for web) or use otp(for app) to verify this as your new email for '.config('app.name').' login.';
        $details['token']       =   $token;

        Mail::to($user->new_email ?? $user->email)->send(new \App\Mail\EmailVerifyMail($details));

        $update = User::where('email',$this->email)->update(['email_verification_string'=>$rand_str,'email_verified'=>false]);
        if($update){
            /**For mobile */
                UserToken::updateOrInsert(['user_id' => $user->id],
                [
                    'token' => $token,
                    'created_at' => Carbon::now(),
                    'type' => 'new_email'
                ]);
            /**For mobile */
            $type = 'success';
            $msg = 'Verification email sent successfully!';
            $this->emitTo('flash-component', 'flashMessage',['type' => $type, 'msg' => $msg]);
            activity()
                ->performedOn($user)
                ->causedBy(auth()->user())
                ->log('Verification email on <b>:subject.email</b> has been sent');
        }
        $this->openCloseResendVerificationEmail();
        $this->emitSelf('$refresh');
    }
}
