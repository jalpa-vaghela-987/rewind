<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\ProjectType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Arr;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Certificate>
 */
class CertificateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id'=>User::inRandomOrder()->first()->id,
            'project_type_id'=>ProjectType::inRandomOrder()->first()->id,
            'name'=> $this->faker->name,
            'quantity'=> $quantity = fake()->numberBetween(1,5),
            'price'=> fake()->numberBetween(1000,5000),
            'approving_body'=>fake()->paragraph(3,true),
            'link_to_certificate'=>fake()->url(),
            'status'=>Arr::random([0,2]),
            'country_id'=>Country::inRandomOrder()->first()->id,
            //'remaining_quantity'=> $quantity,
        ];
    }
}
