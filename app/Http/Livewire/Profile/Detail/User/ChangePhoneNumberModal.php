<?php

namespace App\Http\Livewire\Profile\Detail\User;

use App\Models\Country;
use App\Models\User;
use App\Models\UserToken;
use App\Notifications\SendMessageNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;
use Livewire\Component;

class ChangePhoneNumberModal extends Component
{
    public $phone;
    public $phone_prefix;
    public $showModal=false;
    public User $user;
    public $countries;
    public $phone_with_prefix;
    protected $listeners = ['openCloseChangePhoneModal'];
    public function rules(){
        $prefix = $this->phone_prefix;
        return [
            'phone_prefix'=>'required|exists:countries,phone_prefix',
            'phone'     => ['required','digits:10','numeric',function ($attribute, $value, $fail) use($prefix){
                $checkPhone = User::where('phone_prefix',$prefix)->where('phone',$value)->first();
                if ($checkPhone) {
                    $fail('The '.$attribute.' is already in use.');
                }
            }],
        ];
    }
    public function messages(){
        return [
            'phone.unique'              => 'This phone number is already in use',
        ];
    }
    public function render()
    {
        $this->countries    =   Country::select("code","phone_prefix")
                                ->where('is_active',1)
                                ->orderBy('phone_prefix','ASC')
                                ->get();
        return view('livewire.profile.detail.user.change-phone-number-modal');
    }
    public function mount(){
        $this->phone        =   $this->user->phone;
        $this->phone_prefix =   $this->user->phone_prefix;
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
    /**
     * Save Phone
    */
    public function savePhone(){
        if($this->user->phone==$this->phone && $this->user->phone_prefix==$this->phone_prefix){
            $this->emitSelf('refresh');
        }
        $this->validateOnly('phone');
        $token                      =   rand(1000, 9999);
        $rand_str                   =   Str::random(30);
        $verification_url           =   route('verifyPhoneNumber',['validate_str'=>$rand_str]);
        $message                    =   "Please Verify your phone number by clicking on the link(for web) or by entring OTP(for mobile) mentioned below.\n link:".$verification_url."\n OTP:".$token;
        /**SMS Sending Process */
        $res = $this->sendSMS($this->phone_prefix.$this->phone,$message);
        /**SMS Sending Process End*/
        if(!$res['error']){
            UserToken::updateOrInsert([
                'user_id' => $this->user->id,
                'type' => 'phone'
            ],[
                'token' => $token,
                'created_at' => Carbon::now(),
            ]);
            $this->user->phone_prefix   =   $this->phone_prefix;
            $this->user->phone          =   $this->phone;
            $this->user->phone_verified =   false;
            $this->user->phone_verification_string =   $rand_str;
            if($this->user->save()){
                activity()
                ->performedOn($this->user)
                ->causedBy(auth()->user())
                ->withProperties(['phone_prefix' => $this->phone_prefix, 'phone' => $this->phone,'phone_verification_string'=>$rand_str])
                ->log('Verification link sent to <b>:subject.phone_prefix :subject.phone</b>');
                $message = 'User Phone No. Changed:<a href="'.route('admin.users').'">'.  $this->user->name. '</a>';
                $user = User::find(1);
                $user->notify(new SendMessageNotification($message));
            }else{
                session()->flash('success', 'Technical error, Please try again later.');
            }
        }else{
            session()->flash('error', $res['msg']);
            $this->emitSelf('refresh');
            $this->emit('reRenderParent');
        }
        $this->closeChangePhoneModal();
        $this->emit('reRenderParent');
    }
    public function openCloseChangePhoneModal(){
        $this->showModal = true;
    }

    public function closeChangePhoneModal()
    {
        $this->showModal = false;
    }
}
