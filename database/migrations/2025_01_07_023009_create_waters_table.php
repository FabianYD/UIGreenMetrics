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
        Schema::create('waters', function (Blueprint $table) {
            $table->id();
            $table->string('medidor_id');
            $table->decimal('consumo_total', 10, 2);
            $table->date('fecha_pago');
            $table->string('tipo_consumo');
            $table->string('ubicacion')->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waters');
    }
};
