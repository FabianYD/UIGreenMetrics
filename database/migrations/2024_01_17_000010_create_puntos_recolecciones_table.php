<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gm_wec_puntos_recolecciones', function (Blueprint $table) {
            $table->string('puntoreco_id', 256)->primary();
            $table->string('edificio_id', 256);
            $table->decimal('puntoreco_capacidad', 10, 2)->nullable();
            $table->string('puntoreco_estado', 50)->nullable();
            $table->timestamps();

            $table->foreign('edificio_id')->references('edificio_id')->on('gm_wec_campus_edifcios');
        });
    }

    public function down()
    {
        Schema::dropIfExists('gm_wec_puntos_recolecciones');
    }
};
