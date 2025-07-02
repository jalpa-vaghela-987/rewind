<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'User',
            'email' => 'user@gmail.com',
        ])->assignRole(ROLE_USER);

        User::factory()->create([
            'name' => 'Krishna',
            'email' => 'krishna.godhani@checker-solutions.com',
        ])->assignRole(ROLE_USER);

        User::factory()->create([
            'name' => 'Ashraf',
            'email' => 'mohammad.ashraf@checker-solutions.com',
        ])->assignRole(ROLE_USER);

        User::factory()->create([
            'name' => 'Jalpa',
            'email' => 'jalpa.chudasama@checker-solutions.com',
        ])->assignRole(ROLE_USER);

        User::factory()->create([
            'name' => 'Ashish',
            'email' => 'panchal.ashish@checker-solutions.com',
        ])->assignRole(ROLE_USER);
    }
}
