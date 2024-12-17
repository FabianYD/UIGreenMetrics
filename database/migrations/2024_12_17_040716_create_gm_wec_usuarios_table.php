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
        Schema::create('gm_wec_usuarios', function (Blueprint $table) {
            $table->char('USUARIO_ID', 10)->primary();
            $table->char('NOMBRE_USUARIO', 50);
            $table->char('EMAIL', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gm_wec_usuarios');
    }
};
