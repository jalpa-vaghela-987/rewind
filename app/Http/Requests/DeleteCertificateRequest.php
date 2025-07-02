<?php

namespace App\Http\Requests;

use App\Models\Certificate;
use Illuminate\Foundation\Http\FormRequest;

class DeleteCertificateRequest extends FormRequest
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
            'id'=> 'required|integer|exists:certificates,id'
        ];
    }
    public function withValidator($validator){
        if(!$validator->fails()){
            $validator->after(function ($validator) {
                $certificate    =   Certificate::where('user_id',$this->user()->id)
                                    ->where('id',$this->id)->first();
                if(!$certificate){
                    $validator->errors()->add('id', 'Certificate doesn\'t belong to you!');
                }elseif($certificate->sell_certificate && (($certificate->sell_certificates->count() > 1) || ($certificate->quantity != $certificate->sell_certificate->remaining_units))){
                    $validator->errors()->add('id', 'Certificate can\'t be deleted!');
                }
            });
        }
    }
}
