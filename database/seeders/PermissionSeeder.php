<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([

            [ 'name' => 'user.get'],
            [ 'name' => 'user.get-all'],
            [ 'name' => 'user.add'],
            [ 'name' => 'user.edit'],
            [ 'name' => 'user.delete'],

            [ 'name' => 'balance.get'],
            [ 'name' => 'balance.get-all'],
            [ 'name' => 'balance.add'],
            [ 'name' => 'balance.edit'],
            [ 'name' => 'balance.delete'],

            [ 'name' => 'role.get'],
            [ 'name' => 'role.add'],
            [ 'name' => 'role.edit'],
            [ 'name' => 'role.delete'],
            
            [ 'name' => 'admin.get'],
            [ 'name' => 'admin.add'],
            [ 'name' => 'admin.edit'],
            [ 'name' => 'admin.delete'],

            [ 'name' => 'permission.edit'],
            [ 'name' => 'permission.get'],

            [ 'name' => 'dress.add'],
            [ 'name' => 'dress.get'],
            [ 'name' => 'dress.get-all'],
            [ 'name' => 'dress.delete'],
            [ 'name' => 'dress.edit'],
      
            [ 'name' => 'reservation.add'],
            [ 'name' => 'reservation.get'],
            [ 'name' => 'reservation.get-all'],
            [ 'name' => 'reservation.delete'],
            [ 'name' => 'reservation.edit'],
      
        ]);
    }
}
