<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gm_wec_campus', function (Blueprint $table) {
            $table->string('cam_id', 10)->primary();
            $table->string('cam_nombre', 50)->nullable();
            $table->string('cam_ubicacion', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gm_wec_campus');
    }
};
