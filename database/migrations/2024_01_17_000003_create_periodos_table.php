<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gm_wec_periodos', function (Blueprint $table) {
            $table->string('peri_id', 256)->primary();
            $table->date('per_dia')->nullable();
            $table->date('per_mes')->nullable();
            $table->date('per_anio')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gm_wec_periodos');
    }
};
