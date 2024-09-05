<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'role_id' => 1, // Assuming 1 is the role ID for admin
            'name' => 'Admin',
            'photo' => null,
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
            'active' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('admins')->insert([
            'role_id' => 1, // Assuming 1 is the role ID for admin
            'name' => 'Admin',
            'photo' => null,
            'username' => 'admin2',
            'email' => 'admin2@example.com',
            'password' => Hash::make('12345678'),
            'active' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
