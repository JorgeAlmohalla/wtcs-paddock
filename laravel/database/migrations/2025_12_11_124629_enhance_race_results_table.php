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
    Schema::table('race_results', function (Blueprint $table) {
        // Cambiamos el booleano 'dnf' por un ENUM más completo
        $table->dropColumn('dnf'); 
        $table->enum('status', ['finished', 'dnf', 'dns', 'dsq', '+1 lap', '+2 laps'])->default('finished')->after('grid_position');
        
        // Tiempo de carrera (guardado como texto para permitir "22:09.378")
        $table->string('race_time', 20)->nullable()->after('status');
        
        // Vueltas completadas
        $table->integer('laps_completed')->nullable()->after('race_time');
    });
}

    public function down(): void
{
    Schema::table('race_results', function (Blueprint $table) {
        $table->boolean('dnf')->default(false);
        $table->dropColumn(['status', 'race_time', 'laps_completed']);
    });
}
};
