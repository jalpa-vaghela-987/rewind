<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class BidResource extends JsonResource
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
            'sell_certificate'=> new SellCertificateDashboardResource($this->sell_certificate),
            'user' => new UserResource($this->user),
            'amount' => $this->amount,
            'status' => $this->status,
            'rate' => $this->rate,
            'unit' => $this->unit,
            'expiration_date' => $this->expiration_date,
            'card_detail' => $this->card_detail,
            'price_difference' => $this->priceDifference,
            'difference_type' => $this->differenceType,
            'created_at '=> $this->created_at,
        ];
    }
}
