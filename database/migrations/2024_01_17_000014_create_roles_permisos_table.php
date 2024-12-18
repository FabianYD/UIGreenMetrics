<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gm_wec_roles_permisos', function (Blueprint $table) {
            $table->string('rol_per_id', 256)->primary();
            $table->string('per_id', 256);
            $table->string('rol_id', 256);
            $table->timestamps();

            $table->foreign('per_id')->references('per_id')->on('gm_wec_permisos');
            $table->foreign('rol_id')->references('rol_id')->on('gm_wec_roles');
        });
    }

    public function down()
    {
        Schema::dropIfExists('gm_wec_roles_permisos');
    }
};
