<?php

namespace App\Http\Requests;

use App\Models\UserToken;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class VerifyPhoneNumberOTPRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(!$this->user()->phone_verified)
            return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'otp' => 'required|digits:4|numeric',
        ];
    }
    public function withValidator($validator){
        if(!$validator->fails()){
            $validator->after(function ($validator) {
                $now                    =   Carbon::now();
                $tenMinuteBeforeTime    =   Carbon::now()->subMinutes(10);
                $tokenData  =   UserToken::where('user_id',$this->user()->id)
                                ->where('type','phone')
                                ->where('token',$this->otp)
                                ->first();
                if (empty($tokenData)) {
                    $validator->errors()->add('field', 'The OTP you entered isn\'t correct. Please check the code and try again!');
                }elseif($tokenData->created_at < $tenMinuteBeforeTime || $tokenData->created_at> $now){
                    $validator->errors()->add('field', 'OTP is expired!');
                }
            });
        }
    }
}
