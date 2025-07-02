<?php

namespace App\Http\Requests;

use App\Models\SellCertificate;
use Illuminate\Foundation\Http\FormRequest;

class BuyCertificateRequest extends FormRequest
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
            'sell_certificate_id'    =>  'required|exists:sell_certificates,id',
            'units'             =>  'required|integer',
        ];
    }
    public function withValidator($validator){
        if(!$validator->fails()){
            $validator->after(function ($validator) {
                $certificateData    =   SellCertificate::where('id',$this->sell_certificate_id)->first();
                if($this->units > $certificateData->remaining_units){
                    $validator->errors()->add('units', 'Units are not available!');
                }
                if($this->user()->phone && !$this->user()->phone_verified){
                    $validator->errors()->add('phone', 'Phone number isn\'t verified!');
                }
                if(!$this->user()->creditCard){
                    $validator->errors()->add('card', 'Please add a credit card to buy certificates!');
                }
            });
        }
    }
}
