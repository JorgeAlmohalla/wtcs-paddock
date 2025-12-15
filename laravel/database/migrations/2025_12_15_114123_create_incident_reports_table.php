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
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('race_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reporter_id')->constrained('users'); // Quien reporta
            $table->foreignId('reported_id')->constrained('users'); // A quien reportan
            
            $table->string('lap_corner')->nullable(); // "Lap 4, T1"
            $table->text('description');
            $table->string('video_url');
            
            // Estado del reporte
            $table->enum('status', ['pending', 'investigating', 'resolved', 'dismissed'])->default('pending');
            $table->text('steward_notes')->nullable(); // La decisión del admin
            $table->string('penalty_applied')->nullable(); // "5s" o "Drive Through"
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_reports');
    }
};
