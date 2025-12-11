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
    Schema::create('qualifying_results', function (Blueprint $table) {
        $table->id();
        $table->foreignId('race_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
        
        $table->integer('position'); // 1, 2, 3...
        $table->string('best_time', 20)->nullable(); // "1:09.355"
        $table->string('tyre_compound')->nullable(); // Soft, Medium...
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qualifying_results');
    }
};
