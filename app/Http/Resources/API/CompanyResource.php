<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'name'=>$this->name,
            'user_id'=>$this->user_id,
            'field'=>$this->field,
            'street'=>$this->street,
            'country_id'=>$this->country_id,
            'country'=>new CountryResource($this->country),
            'registration_id'=>$this->registration_id,
            'incorporation_doc_url'=>$this->incorporation_doc_url,
            'city'=>$this->city
        ];
    }
}
