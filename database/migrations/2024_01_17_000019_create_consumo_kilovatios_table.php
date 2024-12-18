<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gm_wec_consumo_kilovatios', function (Blueprint $table) {
            $table->string('conene_id', 256)->primary();
            $table->string('peri_id', 256);
            $table->decimal('conene_kilovatios', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('peri_id')->references('peri_id')->on('gm_wec_periodos');
        });
    }

    public function down()
    {
        Schema::dropIfExists('gm_wec_consumo_kilovatios');
    }
};
