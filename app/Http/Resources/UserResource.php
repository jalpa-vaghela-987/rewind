<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserTokenResource;

class UserResource extends JsonResource
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
            "id"        => $this->id,
            "name"      => $this->name,
            "email"     => $this->email,
            "phone"     => $this->role,
            "company"   => new CompanyResource($this->company),
            // "token"     => new PersonalAccessTokenResource($this->personalAccessToken),
        ];
    }
}
