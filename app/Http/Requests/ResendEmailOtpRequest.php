<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ResendEmailOtpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user())
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
            // 'email' => 'required|email|exists:users,email'
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    // public function messages()
    // {
        // return [
        //     'email.required' => 'Email is required',
        //     'email.email' => 'Email must be a valid email',
        //     'email.exists' => 'Email doesn\'t exist',
        // ];
    // }
    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->user()->email_verified && !$this->user()->registration_step) {
                $validator->errors()->add('email', 'Email is already verified!');
            }
        });
    }

}
