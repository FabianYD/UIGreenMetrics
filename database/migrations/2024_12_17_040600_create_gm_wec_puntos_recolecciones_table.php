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
        Schema::create('gm_wec_puntos_recolecciones', function (Blueprint $table) {
            $table->char('PUNTORECO_ID', 256)->primary();
            $table->char('EDIFICIO_ID', 256);
            $table->decimal('PUNTORECO_CAPACIDAD')->nullable();
            $table->boolean('PUNTORECO_ESTADO')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gm_wec_puntos_recolecciones');
    }
};
