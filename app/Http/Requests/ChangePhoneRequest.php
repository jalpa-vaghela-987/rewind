<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ChangePhoneRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->phone_verified || $this->user()->phone==null)
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
            'phone_prefix'=>'required|exists:countries,phone_prefix',
            'phone'     => ['required','digits:10','numeric'],
        ];
    }
    public function withValidator($validator)
    {
        $prefix     =   $this->phone_prefix;
        $validator->after(function ($validator) use($prefix) {
            $checkPhone = User::where('phone_prefix',$prefix)->where('phone',$this->phone)->first();
            if ($checkPhone) {
                $validator->errors()->add('phone', 'This phone number is already in use.');
            }
        });
    }
}
