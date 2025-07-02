<?php

namespace App\Http\Resources\API;

use App\Http\Resources\BuySmallChartResource;
use App\Http\Resources\CompanyResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BuyIndexCertificateResource extends JsonResource
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
            'id' => $this->id,
            'user_id' => $this->user_id,
            'certificate_id' => $this->certificate_id,
            'project_type_id' => $this->certificate->project_type_id,
            'project_type' => new CompanyResource($this->certificate->project_type),
            'quantity' => $this->certificate->quantity,
            'approving_body' => $this->certificate->approving_body,
            'link_to_certificate' => $this->certificate->link_to_certificate,
            'status' => $this->status,
            'country_id' => $this->certificate->country_id,
            'country'   => new CountryResource($this->certificate->country),
            'price_per_unit' => $this->price_per_unit,
            'name' => $this->certificate->name,
            'remaining_units' => $this->remaining_units,
            'file_path' => $this->certificate->file_path,
            'chart' => new BuySmallChartResource($this->chart),
            'price_difference' => $this->price_difference,
            'price_average' => $this->price_average
        ];
    }
}
