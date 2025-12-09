<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre completo
            $table->string('short_name', 3); // Abreviatura (ej: RBR)
            $table->enum('type', ['works', 'privateer'])->default('privateer');
            $table->string('car_brand')->nullable(); // Ej: Honda
            $table->string('logo_url')->nullable();
            $table->string('primary_color', 7)->nullable(); // Ej: #FF0000
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};