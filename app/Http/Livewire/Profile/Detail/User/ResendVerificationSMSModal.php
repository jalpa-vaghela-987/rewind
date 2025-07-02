<?php

namespace App\Http\Livewire\Profile\Detail\User;

use App\Http\Livewire\Profile\Exception;
use App\Models\UserToken;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Component;

class ResendVerificationSMSModal extends Component
{
    protected $listeners =  ['openCloseResendVerificationSMS'];
    public $showModal  =   false;
    public $user;
    public function render()
    {
        return view('livewire.profile.detail.user.resend-verification-s-m-s-modal');
    }
    public function openCloseResendVerificationSMS(){
        $this->showModal    =   !$this->showModal;
    }
    public function reSendValidationLink(){
        $token                      =   rand(1000, 9999);
        $rand_str                   =   Str::random(30);
        $verification_url           =   route('verifyPhoneNumber',['validate_str'=>$rand_str]);
        $message                    =   "Please Verify your phone number by clicking on the link(for web) or by entring OTP(for mobile) mentioned below.\n link:".$verification_url."\n OTP:".$token;
        $res                        =   $this->sendSMS($this->user->phone_prefix.$this->user->phone,$message);
        /**SMS Sending Process End*/
        if(!$res['error']){
            UserToken::updateOrInsert([
                'user_id' => $this->user->id,
                'type' => 'phone'
            ],[
                'token' => $token,
                'created_at' => Carbon::now(),
            ]);
            $this->user->phone_verification_string =   $rand_str;
            $this->user->phone_verified =   false;
            $this->user->save();
            activity()
            ->performedOn($this->user)
            ->causedBy(auth()->user())
            ->log('SMS sent to <b>:subject.phone</b> sent successfully');
            $type = 'success';
            $msg = 'SMS sent successfully!';
        }else{
            $type = 'error';
            $msg = $res['msg'];
        }
        $this->emitTo('flash-component', 'flashMessage',['type' => $type, 'msg' => $msg]);
        $this->openCloseResendVerificationSMS();
    }
    public function sendSMS($mobile, $message="Test SMS"){
        try{
            $basic   = new \Vonage\Client\Credentials\Basic(env("NEXMO_KEY"), env("NEXMO_SECRET"));
            $client   = new \Vonage\Client($basic);
            $sms    =   new \Vonage\SMS\Message\SMS($mobile, config('app.name'), $message);
            // $response = $client->message()->send([
            //     'to' => '916377906969',
            //     'from' => config('app.name'),
            //     'text' => $message
            // ]);
            $response = $client->sms()->send($sms);
            return ['error'=>false,"msg"=>'sms sent', 'data'=>$response];
        } catch (Exception $e) {
            return ['error'=>true,"msg"=>$e->getMessage(), 'data'=>null];
        }
    }
}
