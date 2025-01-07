<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('waters', function (Blueprint $table) {
            // Primero eliminamos la restricción existente si existe
            DB::statement('ALTER TABLE waters DROP CONSTRAINT IF EXISTS waters_tipo_consumo_check');
            
            // Agregamos los nuevos campos
            if (!Schema::hasColumn('waters', 'porcentaje_tratamiento')) {
                $table->decimal('porcentaje_tratamiento', 5, 2)->default(0);
            }
            
            if (!Schema::hasColumn('waters', 'porcentaje_reciclaje')) {
                $table->decimal('porcentaje_reciclaje', 5, 2)->default(0);
            }
            
            if (!Schema::hasColumn('waters', 'metodo_tratamiento')) {
                $table->string('metodo_tratamiento')->nullable();
            }
            
            if (!Schema::hasColumn('waters', 'uso_final')) {
                $table->string('uso_final')->nullable();
            }
            
            if (!Schema::hasColumn('waters', 'costo')) {
                $table->decimal('costo', 10, 2)->default(0);
            }

            // Actualizamos la restricción de tipo_consumo
            DB::statement("ALTER TABLE waters ADD CONSTRAINT waters_tipo_consumo_check CHECK (tipo_consumo IN ('Potable', 'Tratada', 'Reciclada', 'Lluvia', 'Residual'))");
        });
    }

    public function down()
    {
        Schema::table('waters', function (Blueprint $table) {
            $table->dropColumn([
                'porcentaje_tratamiento',
                'porcentaje_reciclaje',
                'metodo_tratamiento',
                'uso_final',
                'costo'
            ]);
            
            // Restauramos la restricción original si es necesario
            DB::statement('ALTER TABLE waters DROP CONSTRAINT IF EXISTS waters_tipo_consumo_check');
            DB::statement("ALTER TABLE waters ADD CONSTRAINT waters_tipo_consumo_check CHECK (tipo_consumo IN ('potable', 'reciclada', 'tratada', 'lluvia'))");
        });
    }
};
