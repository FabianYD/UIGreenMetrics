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
        Schema::create('gm_wec_parametros', function (Blueprint $table) {
            $table->char('PARAMETRO_ID', 10)->primary();
            $table->char('NOMBRE_PARAMETRO', 50);
            $table->decimal('VALOR_PARAMETRO', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gm_wec_parametros');
    }
};
