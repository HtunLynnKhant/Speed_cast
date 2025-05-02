<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTableAddRolesAndIsActive extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Modify the 'role' column to include 'superadmin', 'admin', 'client', 'driver'
            $table->enum('role', ['superadmin', 'admin', 'client', 'driver'])->default('admin')->change();

            // Add 'is_active' column to track user activation status
            $table->boolean('is_active')->default(true)->after('role');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the 'role' and 'is_active' columns if rolling back
            $table->dropColumn('role');
            $table->dropColumn('is_active');
        });
    }
}
