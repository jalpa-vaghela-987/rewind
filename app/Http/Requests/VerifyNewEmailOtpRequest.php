<?php

namespace App\Http\Requests;

use App\Models\UserToken;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class VerifyNewEmailOtpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->new_email){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'token' => 'required|digits:4|numeric',
        ];
    }
    public function withValidator($validator){
        $validator->after(function ($validator) {
            $now                    =   Carbon::now();
            $tenMinuteBeforeTime    =   Carbon::now()->subMinutes(10);
            $tokenData  =   UserToken::where('user_id',$this->user()->id)
                            ->where('type','new_email')
                            ->where('token',$this->token)
                            ->first();
            if (empty($tokenData)) {
                $validator->errors()->add('token', 'The code you entered isn\'t right. Please check the code and try again!');
            }elseif($tokenData->created_at < $tenMinuteBeforeTime || $tokenData->created_at> $now){
                $validator->errors()->add('token', 'Token is expired!');
            }
        });
    }
}
