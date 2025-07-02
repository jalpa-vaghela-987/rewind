<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class RegisterStepThreeRequest extends FormRequest
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
        else
            return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'          => 'required',
            'phone_prefix'  => 'required|exists:countries,phone_prefix',
            'phone'         => 'required|numeric|unique:users,phone,'.$this->user()->id
        ];
    }
    public function withValidator($validator)
    {
        if(!$validator->fails()){
            $prefix     =   $this->phone_prefix;
            $validator->after(function ($validator) use($prefix) {
                $checkPhone = User::where('id','<>',$this->user()->id)->where('phone_prefix',$prefix)->where('phone',$this->phone)->first();
                if ($checkPhone) {
                    $validator->errors()->add('phone', 'This phone number is already in use.');
                }
            });
        }
    }
}
