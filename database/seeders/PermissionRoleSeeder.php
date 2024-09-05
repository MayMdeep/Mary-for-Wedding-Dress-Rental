<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionRoleSeeder extends Seeder
{
    public function run(): void
    {
        $adminRoleId = 1;
        $user_permissionsIds = [
        
        ];

        for ($i = 1; $i <= 50; $i++) {
            if (array_key_exists($i, $user_permissionsIds)) {
                DB::table('permission_roles')->insert([
                    'permission_id' => $i,
                    'role_id' => 4,
                ]);
            }

            DB::table('permission_roles')->insert([
                'permission_id' => $i,
                'role_id' => 1,
            ]);
        }
    }
}
