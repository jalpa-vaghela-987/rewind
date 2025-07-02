<?php

namespace App\Http\Requests;

use Faicchia\IbanValidation\Rules\Iban;
use Illuminate\Foundation\Http\FormRequest;

class AddBankAccountRequest extends FormRequest
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
            'name'              => 'required',
            'iban'              => ['required','unique:bank_details,iban',new Iban(),'min:22','max:34'],
            'beneficiary_name'  => 'nullable',
            'bic'               => ['required','max:11'],
            'country_id'        => ['required','exists:countries,id'],
        ];
    }
}
