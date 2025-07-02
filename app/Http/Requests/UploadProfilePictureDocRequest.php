<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadProfilePictureDocRequest extends FormRequest
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
            'base64_image'      => 'required',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $ext        =   explode('/', mime_content_type($this->base64_image))[1];
            if(!in_array($ext,['png', 'gif', 'bmp', 'svg','jpg', 'jpeg'])){
                $validator->errors()->add('email', 'Please provide a valid image.');
            }
        });
    }
}
