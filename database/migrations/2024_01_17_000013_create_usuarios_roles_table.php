<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gm_wec_usuarios_roles', function (Blueprint $table) {
            $table->string('usu_rol_id', 256)->primary();
            $table->string('rol_id', 256);
            $table->string('usu_id', 12);
            $table->timestamps();

            $table->foreign('rol_id')->references('rol_id')->on('gm_wec_roles');
            $table->foreign('usu_id')->references('usu_id')->on('gm_wec_usuarios');
        });
    }

    public function down()
    {
        Schema::dropIfExists('gm_wec_usuarios_roles');
    }
};
