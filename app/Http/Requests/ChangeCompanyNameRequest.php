<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeCompanyNameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->company)
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
            'name'=>'required'
        ];
    }
    public function withValidator($validator){
        if(!$validator->fails()){
            $validator->after(function ($validator) {
                if($this->name == $this->user()->company->name){
                    $validator->errors()->add('name', 'Nothing to update!');
                }
            });
        }
    }
}
