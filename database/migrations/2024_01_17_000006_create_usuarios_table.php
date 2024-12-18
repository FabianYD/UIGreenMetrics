<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gm_wec_usuarios', function (Blueprint $table) {
            $table->string('usu_id', 12)->primary();
            $table->string('usu_nombre', 50)->nullable();
            $table->string('usu_correo', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gm_wec_usuarios');
    }
};
