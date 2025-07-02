<?php

namespace Database\Factories;

use App\Models\Certificate;
use App\Models\SellCertificate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SellCertificate>
 */
class SellCertificateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $certificate = Certificate::where('quantity','>',0)->inRandomOrder()->first();
        if(!empty($certificate)){
            if (!SellCertificate::firstWhere(['certificate_id' => $certificate->id]))
            {
                $units = mt_rand(1,$certificate->quantity);
                return [
                    'certificate_id'=>$certificate->id,
                    'user_id'=>$certificate->user_id,
                    'units'  => $units,
                    'is_main'  => true,
                    'remaining_units'  => $units,
                    'price_per_unit'=>$units*$certificate->price,
                ];
            }else{
                return [];
            }
        }else{
            return [];
        }

    }
}
