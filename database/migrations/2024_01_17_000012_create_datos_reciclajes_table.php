<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gm_wec_datos_reciclajes', function (Blueprint $table) {
            $table->string('reciagua_id', 256)->primary();
            $table->string('puntoreco_id', 256);
            $table->date('reciagua_fecha')->nullable();
            $table->decimal('reciagua_cantidadrecolectada', 10, 2)->nullable();
            $table->decimal('reciagua_cantidadprocesada', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('puntoreco_id')->references('puntoreco_id')->on('gm_wec_puntos_recolecciones');
        });
    }

    public function down()
    {
        Schema::dropIfExists('gm_wec_datos_reciclajes');
    }
};
