<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $idProofName = Str::random(10).'.jpg';
        UploadedFile::fake()->image('id_proof.jpg')->storeAs('public/images',$idProofName);
        $profilePhotoName = Str::random(10).'.jpg';
        UploadedFile::fake()->image('avatar.jpg')->storeAs('public/images',$profilePhotoName);
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make("password"), // password
            'id_proof' => 'images/'.$idProofName,
            'street' => $this->faker->streetName,
            'country_id' => Country::inRandomOrder()->first()->id,
            'city' => $this->faker->city,
            'phone' => $this->faker->phoneNumber,
            'phone_verified'=>true,
            'status' => 1,
            'email_verified' => 1,
            'profile_photo_path' => 'images/'.$profilePhotoName,
            'phone_prefix' => '91',
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (User $user) {
            //
        })->afterCreating(function (User $user) {
            $user->assignRole(ROLE_USER);
        });
    }
}
