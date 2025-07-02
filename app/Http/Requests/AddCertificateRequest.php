<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCertificateRequest extends FormRequest
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

    public function rules()
    {
        $this->price_per_unit = ($this->quantity>0) ? round($this->price/$this->quantity,2) : 0;
        return [
            'project_type_id' => ['required'],
            'name' => ['required'],
            'country_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer'],
            'price' => ['required', 'numeric', 'gte:1'],
            'link_to_certificate' => ['nullable', 'url']
        ];
    }

    public function messages()
    {
        return [
            'project_type_id.required' => "Project type required.",
            'name.required' => 'Project name required.',
            'country_id.required' => 'Country required.',
            'quantity.required' => 'Quantity required.',
            'quantity.integer' => 'Quantity should be integer.',
            'price.required' => 'Price required.',
            'price.numeric' => 'Price should be numeric.',
            'link_to_certificate.url' => 'Invalid url'

        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $pricePerUnit = round($this->price/$this->quantity,2);
            if($pricePerUnit < 1)
            {
                $validator->errors()->add('price_per_unit', 'The price per unit is '.$pricePerUnit.', This must be greater than or equal to 1.');
            }
        });
    }

}
