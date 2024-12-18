<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gm_wec_metas_parametros', function (Blueprint $table) {
            $table->string('meta_id', 256)->primary();
            $table->string('cam_id', 10);
            $table->decimal('meta_reciclaje', 10, 2)->nullable();
            $table->string('meta_estado', 50)->nullable();
            $table->string('meta_titulo', 100)->nullable();
            $table->timestamps();

            $table->foreign('cam_id')->references('cam_id')->on('gm_wec_campus');
        });
    }

    public function down()
    {
        Schema::dropIfExists('gm_wec_metas_parametros');
    }
};
