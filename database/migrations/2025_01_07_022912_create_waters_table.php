<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('waters', function (Blueprint $table) {
            $table->id();
            $table->string('medidor_id')->nullable();
            $table->string('tipo_consumo');
            $table->decimal('consumo_total', 10, 2);
            $table->date('fecha_pago');
            $table->string('ubicacion')->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waters');
    }
};
