<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gm_wec_campus_facultad', function (Blueprint $table) {
            $table->string('campus_facultad_id', 256)->primary();
            $table->string('facu_id', 256);
            $table->string('cam_id', 10);
            $table->timestamps();

            $table->foreign('facu_id')->references('facu_id')->on('gm_wec_facultad');
            $table->foreign('cam_id')->references('cam_id')->on('gm_wec_campus');
        });
    }

    public function down()
    {
        Schema::dropIfExists('gm_wec_campus_facultad');
    }
};
