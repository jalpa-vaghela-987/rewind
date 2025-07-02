<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadIncorporationDocRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user() && $this->user()->company)
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
            'incorporation_document' => 'required|image|max:5120',
        ];
    }
}
