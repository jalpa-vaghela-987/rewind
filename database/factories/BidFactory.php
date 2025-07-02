<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Certificate;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bid>
 */
class BidFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'certificate_id'=>Certificate::inRandomOrder()->first()->id,
            'user_id'=>User::inRandomOrder()->first()->id,
            'amount'=>fake()->numberBetween($min = 1500, $max = 6000),
        ];
    }
}
