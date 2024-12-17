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
        Schema::create('gm_wec_datos_reciclajes', function (Blueprint $table) {
            $table->char('RECICLAJE_ID', 10)->primary();
            $table->char('CAMPUS_ID', 10);
            $table->char('TIPO_RECICLAJE', 50);
            $table->decimal('CANTIDAD_RECICLADA', 8, 2)->nullable();
            $table->timestamps();

            $table->foreign('CAMPUS_ID')->references('CAM_ID')->on('gm_wec_campus')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gm_wec_datos_reciclajes');
    }
};
