<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Arr;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CardDetail>
 */
class CardDetailFactory extends Factory
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
            'card_no'=>random_int(1000000000000000,9999999999999999),
            'card_holder_name'=>fake()->name(),
            'expiry_month'=> fake()->numberBetween(1,12),
            'expiry_year'=>random_int(2025,2050),
            'cvv'=>fake()->numberBetween(100,999),
            'is_active'=>Arr::random([0,1])
        ];
    }
}
