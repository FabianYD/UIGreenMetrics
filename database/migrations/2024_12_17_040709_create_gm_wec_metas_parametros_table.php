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
        Schema::create('gm_wec_metas_parametros', function (Blueprint $table) {
            $table->char('META_PARAMETRO_ID', 10)->primary();
            $table->char('INDICADOR_ID', 10);
            $table->decimal('VALOR_META', 8, 2)->nullable();
            $table->timestamps();

            $table->foreign('INDICADOR_ID')->references('INDICADOR_ID')->on('gm_wec_indicadores')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gm_wec_metas_parametros');
    }
};
