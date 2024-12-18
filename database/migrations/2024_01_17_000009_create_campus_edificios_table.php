<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gm_wec_campus_edifcios', function (Blueprint $table) {
            $table->string('edificio_id', 256)->primary();
            $table->string('cam_id', 10);
            $table->string('edificio_nombre', 100)->nullable();
            $table->integer('edificio_num')->nullable();
            $table->timestamps();

            $table->foreign('cam_id')->references('cam_id')->on('gm_wec_campus');
        });
    }

    public function down()
    {
        Schema::dropIfExists('gm_wec_campus_edifcios');
    }
};
