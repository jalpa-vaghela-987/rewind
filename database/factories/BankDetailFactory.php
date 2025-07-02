<?php

namespace Database\Factories;

use App\Models\BankDetail;
use App\Models\Certificate;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Country;
use Str,Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BankDetail>
 */
class BankDetailFactory extends Factory
{
    protected $model = BankDetail::class;

    private static $userId = 1;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->title." bank",
            'bic' => Str::random(6),
            'iban' => Str::random(20),
            'user_id' => self::$userId++,
            'country_id' => Country::inRandomOrder()->first()->value('id'),
            'beneficiary_name' => fake()->name(),
            'is_active' => 1,
            'is_primary' => 1
        ];
    }
}
