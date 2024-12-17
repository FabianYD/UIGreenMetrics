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
        Schema::create('gm_wec_tipo_fuente', function (Blueprint $table) {
            $table->char('TIPOFUE_ID', 256)->primary(); // Cambiar a 256 para coincidir con la FK
            $table->string('NOMBRE_TIPO_FUENTE', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gm_wec_tipo_fuente');
    }
};
