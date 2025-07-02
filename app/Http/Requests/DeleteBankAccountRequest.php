<?php

namespace App\Http\Requests;

use App\Models\BankDetail;
use Illuminate\Foundation\Http\FormRequest;

class DeleteBankAccountRequest extends FormRequest
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
            'id'=>'required|integer|exists:bank_details,id'
        ];
    }
    public function withValidator($validator){
        if(!$validator->fails()){
            $validator->after(function($validator){
                $bank =    BankDetail::where('id',$this->id)->where('user_id',$this->user()->id)->first();
                if(empty($bank)){
                    $validator->errors()->add('id', 'Bank account not found!');
                }
            });
        }
    }
}
