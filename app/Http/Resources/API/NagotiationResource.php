<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class NagotiationResource extends JsonResource
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

          "id" => $this->id,
          "name" => $this->user->name,
          "certificate_name" => $this->certificate->name,
          "sell_certificate" => new SellCertificateDashboardResource($this->sell_certificate),
          "date" => $this->created_at,
          "initial_price" => $this->certificate->price,
          "initial_quantity" => $this->certificate->quantity,
          "offer_price" => $this->rate,
          "offer_quantity" => $this->unit,
          "status" => $this->status,
          "certificate_id" => $this->certificate_id,
          'user_id'=>$this->user_id,
          "expiration_date" => $this->expiration_date,
          "card_detail_id" => $this->card_detail_id,
          "counterOffer" => $this->counterOffer

        ];
        // return parent::toArray($request);
    }
}
