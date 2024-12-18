<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gm_wec_generacion_electrica', function (Blueprint $table) {
            $table->string('genele_id', 256)->primary();
            $table->string('tipofue_id', 256);
            $table->string('peri_id', 256);
            $table->string('campus_facultad_id', 256);
            $table->decimal('genele_kilovatios', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('tipofue_id')->references('tipofue_id')->on('gm_wec_tipo_fuente');
            $table->foreign('peri_id')->references('peri_id')->on('gm_wec_periodos');
            $table->foreign('campus_facultad_id')->references('campus_facultad_id')->on('gm_wec_campus_facultad');
        });
    }

    public function down()
    {
        Schema::dropIfExists('gm_wec_generacion_electrica');
    }
};
