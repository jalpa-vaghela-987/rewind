<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'name' => 'buy.index',
                'guard_name' => 'web'
            ],
            [
                'name' => 'buy.create',
                'guard_name' => 'web'
            ],
            [
                'name' => 'buy.show',
                'guard_name' => 'web'
            ],
            [
                'name' => 'sell.index',
                'guard_name' => 'web'
            ],
            [
                'name' => 'sell.create',
                'guard_name' => 'web'
            ],
            [
                'name' => 'sell.edit',
                'guard_name' => 'web'
            ],
            [
                'name' => 'sell.show',
                'guard_name' => 'web'
            ],
            [
                'name' => 'offer.index',
                'guard_name' => 'web'
            ],
            [
                'name' => 'offer.edit',
                'guard_name' => 'web'
            ],
            [
                'name' => 'portfolio.index',
                'guard_name' => 'web'
            ],
        ];
        foreach ($permissions as $permission) {
//            dd($permission);
            Permission::create($permission);
        }

    }
}
