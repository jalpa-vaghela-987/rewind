<?php

namespace App\Http\Resources;

use App\Http\Resources\API\CertificateResource;
use App\Http\Resources\API\SellCertificateDashboardResource;
use App\Http\Resources\API\SellCertificateResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            'name'=>$this->name,
            'sell_certificate'=>new SellCertificateDashboardResource($this->sell_certificate),
            'buyer'=>new UserResource($this->buyer),
            'seller'=>new UserResource($this->seller),
            'quantity'=>$this->quantity,
            'amount'=>$this->amount,
            'status'=>$this->status,
            'stripe_status'=>$this->stripe_status,
            'card_detail'=>new CardDetailResource($this->card_detail),
            'seller_bank_detail'=>new BankDetailResource($this->seller_bank_detail),
            'ip_address'=>$this->ip_address,
            'price_average'=>$this->price_average,
            'price_difference'=>$this->price_difference,
            'created_at'=>$this->created_at,
        ];
    }
}
