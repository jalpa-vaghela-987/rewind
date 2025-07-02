<?php

namespace App\Http\Resources\API;

use App\Http\Resources\CompanyResource;
use App\Http\Resources\API\SellCertificateResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id'=>$this->id,
            'user_id'=>$this->user_id,
            // 'user'=>new UserResource($this->user),
            'project_type_id'=>$this->project_type_id,
            'project_type'=>new ProjectTypeResource($this->project_type),
            'country_id'=>$this->country_id,
            'country'   => new CountryResource($this->country),
            'name'=>$this->name,
            'description'=>$this->description,
            'file_path'=>$this->file_path,
            'price'=>$this->price,
            'quantity'=>$this->quantity,
            'approving_body'=>$this->approving_body,
            'link_to_certificate'=>$this->link_to_certificate,
            'status' =>$this->status,
            'total'=>$this->total,
            'price_diff_percentage'=>$this->price_diff_percentage,
            'last_sell_certificate'   => new LastSellCertificateResource($this->last_sell_certificate),
        ];
        // return parent::toArray($request);
    }
}
