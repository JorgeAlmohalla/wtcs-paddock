<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('race_results', function (Blueprint $table) {
            $table->id();
            // Relaciones
            $table->foreignId('race_id')->constrained()->cascadeOnDelete();
            // Usamos unsignedBigInteger manual para evitar errores si el usuario se borra
            $table->unsignedBigInteger('user_id'); 
            $table->unsignedBigInteger('team_id')->nullable(); 
            
            // Datos deportivos
            $table->integer('position');
            $table->integer('grid_position')->nullable();
            $table->boolean('fastest_lap')->default(false);
            $table->integer('penalty_seconds')->default(0);
            $table->boolean('dnf')->default(false); // Did Not Finish
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('race_results');
    }
};