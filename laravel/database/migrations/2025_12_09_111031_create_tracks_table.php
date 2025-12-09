<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre del circuito
            $table->string('country_code', 2)->nullable(); // Ej: ES
            $table->string('layout_image_url')->nullable(); // Foto del trazado
            $table->decimal('length_km', 4, 3)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracks');
    }
};