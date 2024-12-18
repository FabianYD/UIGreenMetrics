<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gm_wec_tipo_energia', function (Blueprint $table) {
            $table->string('tipene_id', 256)->primary();
            $table->string('generic_tipoene_id', 256);
            $table->string('tipene_tipo', 50)->nullable();
            $table->string('tipene_descripcion', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gm_wec_tipo_energia');
    }
};
