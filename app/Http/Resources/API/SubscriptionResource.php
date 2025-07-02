<?php

namespace App\Http\Resources\API;

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
            'certificate'=>new CertificateResource($this->certificate),
            'buyer'=>new UserResource($this->buyer),
            'seller'=>new UserResource($this->seller),
            'quantity'=>$this->quantity,
            'amount'=>$this->amount,
            'status'=>$this->status,
            'stripe_status'=>$this->stripe_status,
            'card_detail'=>new CardDetailResource($this->card_detail),
            'buyer_bank_detail'=>new BankDetailResource($this->buyer_bank_detail),
            'seller_bank_detail'=>new BankDetailResource($this->seller_bank_detail),
            'ip_address'=>$this->ip_address,
            'created_at'=>$this->created_at
        ];
    }
}
