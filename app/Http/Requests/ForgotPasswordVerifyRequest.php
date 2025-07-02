<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\UserToken;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordVerifyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'email'=>'required|email|exists:users,email',
            'code' => 'required|exists:user_tokens,token',
        ];
    }
    public function withValidator($validator)
    {
        if (!$validator->fails()) {
            $validator->after(function ($validator) {
                $now                    =   Carbon::now();
                $tenMinuteBeforeTime    =   Carbon::now()->subMinutes(10);
                $user       =   User::where('email',$this->email)->first();
                $tokenData  =   UserToken::where('user_id',$user->id)
                                ->where('type','forgot_password')
                                ->where('token',$this->code)
                                ->first();
                if (empty($tokenData)) {
                    $validator->errors()->add('code', 'The code you entered isn\'t correct. Please check the code and try again!');
                }elseif($tokenData->created_at < $tenMinuteBeforeTime || $tokenData->created_at> $now){
                    $validator->errors()->add('code', 'Your verification code is expired, please resend it!');
                }
            });
        }
    }
}
