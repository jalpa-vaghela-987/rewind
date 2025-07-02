<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class SellCertificateResource extends JsonResource
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
                'certificate'=>new CertificateResource($this->certificate),
                'certificate_name'=>$this->certificate->name,
                'user_id'=>$this->user_id,
                'user'=> new UserResource($this->certificate->user),
                'units'=>$this->units,
                'remaining_units'=>$this->remaining_units,
                'price_per_unit'=>$this->price_per_unit,
                'is_main'=>$this->is_main,
                'status'=>$this->status,
                'price_average'=>$this->price_average,
                'price_difference'=>$this->price_difference,
                'chart'=>$request->days ? $this->getChartData($request->days) :$this->getChartData('1D'),
                'created_at'=>$this->created_at,
        ];
    }
}
