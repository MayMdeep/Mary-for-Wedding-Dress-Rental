<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'Admin','website'=>'0']);
        Role::create(['name' => 'User','website'=>'1']);
    }
}
