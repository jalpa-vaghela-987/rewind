<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class AddCompanyDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->company)
            return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $acceptedMimes   =   ['png', 'gif', 'bmp', 'svg','jpg', 'jpeg', 'webp','pdf'];
        return [
            'name'=>'required',
            'field' => 'required',
            'registration_id' => 'required',
            'incorporation_doc' => ['required',File::types($acceptedMimes)->max(5 * 1024)]
        ];
    }
}
