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
        Schema::table('users', function (Blueprint $table) {
            // Añadimos la columna nueva JSON
            $table->json('roles')->nullable()->after('role');
        });
        
        // Migrar datos antiguos (Script rápido inline)
        // Cogemos lo que había en 'role' y lo metemos en el array 'roles'
        DB::statement("UPDATE users SET roles = JSON_ARRAY(role)");

        Schema::table('users', function (Blueprint $table) {
            // Borramos la vieja
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
