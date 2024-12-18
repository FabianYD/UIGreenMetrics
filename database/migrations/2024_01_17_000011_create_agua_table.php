<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gm_wec_agua', function (Blueprint $table) {
            $table->string('agua_id', 256)->primary();
            $table->string('puntoreco_id', 256);
            $table->string('tipo_agua', 50)->nullable();
            $table->decimal('cantidad_agua', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('puntoreco_id')->references('puntoreco_id')->on('gm_wec_puntos_recolecciones');
        });
    }

    public function down()
    {
        Schema::dropIfExists('gm_wec_agua');
    }
};
