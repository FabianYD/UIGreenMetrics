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
        Schema::create('gm_wec_campus', function (Blueprint $table) {
            $table->char('CAM_ID', 10)->primary();
            $table->char('CAM_NOMBRE', 50);
            $table->char('CAM_UBICACION', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gm_wec_campus');
    }
};
