<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('water_collection_points', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('ubicacion');
            $table->decimal('capacidad', 8, 2);
            $table->decimal('agua_tratada', 8, 2)->default(0);
            $table->decimal('agua_reciclada', 8, 2)->default(0);
            $table->decimal('agua_reutilizada', 8, 2)->default(0);
            $table->string('estado')->default('activo');
            $table->decimal('eficiencia', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('water_collection_points');
    }
};
