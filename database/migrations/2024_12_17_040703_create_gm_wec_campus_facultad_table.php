<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gm_wec_campus_facultad', function (Blueprint $table) {
            $table->char('FACULTAD_ID', 10)->primary();
            $table->char('FACULTAD_NOMBRE', 50);
            $table->char('CAMPUS_ID', 10);
            $table->timestamps();

            $table->foreign('CAMPUS_ID')->references('CAM_ID')->on('gm_wec_campus')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gm_wec_campus_facultad');
    }
};
