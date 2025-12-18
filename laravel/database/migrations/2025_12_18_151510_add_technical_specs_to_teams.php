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
        Schema::table('teams', function (Blueprint $table) {
            $table->string('tech_chassis')->nullable(); // "Sedan (1998-2004)"
            $table->string('tech_engine')->nullable(); // "2.0L I4 Turbo"
            $table->string('tech_power')->nullable(); // "320 bhp"
            $table->string('tech_drivetrain')->nullable(); // "FF"
            $table->string('tech_gearbox')->nullable(); // "6-Speed Seq"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            //
        });
    }
};
