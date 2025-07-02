<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class VerifyBidRequest extends FormRequest
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
        $rules = [];
        if($this->has('status') && $this->status == 3)
        {
            $rules['price'] = 'required|numeric';
            $rules['quantity'] = 'required|integer';
        }

        $rules = array_merge($rules, [
            'bid_id' => 'required|exists:bids,id',
            'status' => 'required|in:1,2,3,4',
            'price' => 'required_if:status,==,3',
            'quantity' => 'required_if:status,==,3',
        ]);

        return $rules;
    }

    public function messages(): array
    {
        return [
            'bid_id.required' => "Bid Id required.",
            'bid_id.exists' => "Invalid Bid.",
            'status.required' => "Status is required.",
            'price.required_if' => "Price is required.",
            'quantity.required_if' => "Quantity is required.",
        ];
    }
}
