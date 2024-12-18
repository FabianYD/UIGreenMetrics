<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gm_wec_indicadores', function (Blueprint $table) {
            $table->string('indicador_id', 256)->primary();
            $table->string('cam_id', 10);
            $table->string('indicador_nombre', 100)->nullable();
            $table->date('indicador_fecha')->nullable();
            $table->timestamps();

            $table->foreign('cam_id')->references('cam_id')->on('gm_wec_campus');
        });
    }

    public function down()
    {
        Schema::dropIfExists('gm_wec_indicadores');
    }
};
