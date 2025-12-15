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
        Schema::table('qualifying_results', function (Blueprint $table) {
            $table->string('car_name')->nullable()->after('team_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qualifying_results', function (Blueprint $table) {
            //
        });
    }
};
