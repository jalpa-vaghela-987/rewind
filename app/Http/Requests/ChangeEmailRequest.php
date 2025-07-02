<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeEmailRequest extends FormRequest
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
            'new_email' =>['required','email','different:email','unique:users,email','unique:users,new_email,'.$this->user()->id]
        ];
    }
    // NOT TO DELETE
    // public function withValidator($validator){
    //     if (!$validator->fails()) {
    //         $validator->after(function ($validator) {
    //             if (!$this->user()->email_verified) {
    //                 $validator->errors()->add('new_email', 'Email verification already pending!');
    //             }
    //         });
    //     }
    // }
}
