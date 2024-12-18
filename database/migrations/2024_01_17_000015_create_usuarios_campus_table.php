<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gm_wec_usuarios_campus', function (Blueprint $table) {
            $table->string('usu_campus_id', 256)->primary();
            $table->string('cam_id', 10);
            $table->string('usu_id', 12);
            $table->timestamps();

            $table->foreign('cam_id')->references('cam_id')->on('gm_wec_campus');
            $table->foreign('usu_id')->references('usu_id')->on('gm_wec_usuarios');
        });
    }

    public function down()
    {
        Schema::dropIfExists('gm_wec_usuarios_campus');
    }
};
