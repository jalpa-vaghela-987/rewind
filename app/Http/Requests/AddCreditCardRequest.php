<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCreditCardRequest extends FormRequest
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
        $expiry_month   =   date('m');
        $expiry_year    =   date('Y');

        if(isset($this->expiry_year)){
            $expiry_year    =   $this->expiry_year;
        }
        if(isset($this->expiry_month)){
            $expiry_month    =   $this->expiry_month;
        }
        $this->merge(['expiry' => $expiry_month.'/31/'.$expiry_year]);
        return [
            'card_no'           => ['required','unique:card_details,card_no,NULL,id,user_id,'.auth()->user()->id,'digits:16'],
            'card_holder_name'  => 'nullable',
            'expiry_month'      => ['required','numeric','min:01','max:12'],
            'expiry_year'       => ['required','numeric','digits:4','max:2099'],
            'cvv'               => ['required','digits:3'],
            'expiry'            => 'after:'.date('m/d/Y'),
        ];
    }
}
