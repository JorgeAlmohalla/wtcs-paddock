<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('races', function (Blueprint $table) {
            $table->id();
            // Relación con el circuito
            $table->foreignId('track_id')->constrained()->cascadeOnDelete();
            $table->integer('round_number');
            $table->string('title')->nullable(); // "GP de Silverstone"
            $table->dateTime('race_date'); // Para la cuenta atrás
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('races');
    }
};