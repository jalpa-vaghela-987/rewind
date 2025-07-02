<?php

namespace App\Http\Resources\API;

use App\Http\Resources\API\CompanyResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public static $wrap = 'data';

    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            'id_proof' => $this->id_proof,
            'street' => $this->street,
            'country_id' => $this->country_id,
            'city' => $this->city,
            'phone' => $this->phone,
            'status' => $this->status,
            'profile_photo_path' => $this->profile_photo_path,
            'stripe_id' => $this->stripe_id,
            'stripe_customer_id' => $this->stripe_customer_id,
            'pm_type' => $this->pm_type,
            'pm_last_four' => $this->pm_last_four,
            'trial_ends_at' => $this->trial_ends_at,
            'phone_verified' => $this->phone_verified,
            'email_verified' => $this->email_verified,
            'phone_prefix' => $this->phone_prefix,
            "company"   => new CompanyResource($this->company),
            "country"   => new CountryResource($this->country),
            'token' => $this->token??$this->token,
            'registration_step'=> $this->registration_step,
        ];
    }
}
