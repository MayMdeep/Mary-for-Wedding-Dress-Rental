<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE VIEW role_permission_views AS
            SELECT 
                permission_roles.permission_id, 
                roles.name AS role, 
                permissions.name AS permission, 
                roles.id
            FROM 
                permission_roles 
            INNER JOIN roles ON permission_roles.role_id = roles.id 
            INNER JOIN permissions ON permission_roles.permission_id = permissions.id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    DB::statement('DROP VIEW IF EXISTS role_permission_views');

    }
};
