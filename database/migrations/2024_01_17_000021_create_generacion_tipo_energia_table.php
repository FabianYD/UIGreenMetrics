<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gm_wec_generacion_tipo_energia', function (Blueprint $table) {
            $table->string('generic_tipoene_id', 256)->primary();
            $table->string('genele_id', 256);
            $table->timestamps();

            $table->foreign('genele_id')->references('genele_id')->on('gm_wec_generacion_electrica');
        });

        // Agregar la llave foránea a gm_wec_tipo_energia después de crear la tabla gm_wec_generacion_tipo_energia
        Schema::table('gm_wec_tipo_energia', function (Blueprint $table) {
            $table->foreign('generic_tipoene_id')->references('generic_tipoene_id')->on('gm_wec_generacion_tipo_energia');
        });
    }

    public function down()
    {
        // Eliminar primero la llave foránea de gm_wec_tipo_energia
        Schema::table('gm_wec_tipo_energia', function (Blueprint $table) {
            $table->dropForeign(['generic_tipoene_id']);
        });

        Schema::dropIfExists('gm_wec_generacion_tipo_energia');
    }
};
