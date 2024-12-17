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
        Schema::create('gm_wec_agua', function (Blueprint $table) {
            $table->char('AGUA_ID', 256)->primary();
            $table->char('PUNTORECO_ID', 256);
            $table->char('TIPO_AGUA', 100)->nullable();
            $table->decimal('CANTIDAD_AGUA')->nullable();
            $table->timestamps();

            $table->foreign('PUNTORECO_ID')
                  ->references('PUNTORECO_ID')
                  ->on('gm_wec_puntos_recolecciones')
                  ->onDelete('restrict')
                  ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gm_wec_agua');
    }
};
