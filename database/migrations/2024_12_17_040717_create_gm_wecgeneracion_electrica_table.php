<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gm_wecgeneracion_electrica', function (Blueprint $table) {
            $table->char('GENELE_ID', 256)->primary();
            $table->char('TIPOFUE_ID', 256);
            $table->char('PERI_ID', 256);
            $table->char('CAMPUS_FACULTAD_ID', 256);
            $table->decimal('GENELE_KILOVATIOS', 8, 2)->nullable();
            $table->timestamps();

            $table->foreign('TIPOFUE_ID')
                  ->references('TIPOFUE_ID')
                  ->on('gm_wec_tipo_fuente')
                  ->onDelete('restrict')
                  ->onUpdate('restrict');
            
            // ... otras llaves foráneas ...
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gm_wecgeneracion_electrica');
    }
};
