<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginRequest extends FormRequest
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
            'type'=>'required|in:azure,google,apple',
            'access_token'=>'required'
        ];
    }
    // public function withValidator($validator)
    // {
    //     $validator->after(function ($validator) {
    //         $socialUser     =   Socialite::driver($this->type)->userFromToken($this->access_token);
    //         if(!$socialUser){
    //             $validator->errors()->add('access_token', 'Token invalid');
    //         }
    //     });
    // }
}
