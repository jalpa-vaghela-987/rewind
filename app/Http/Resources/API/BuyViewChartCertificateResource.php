<?php

namespace App\Http\Resources\API;

use App\Http\Resources\CompanyResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BuyViewChartCertificateResource extends JsonResource
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
            'data' => $this['data'],
            'labels' => $this['labels'],
            'maxValue' => $this['maxValue'],
            'stepSize' => $this['stepSize']
        ];
    }
}
