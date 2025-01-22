<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoTratamientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\TipoTratamiento::query()->delete();
        
        $tipos = [
            [
                'TIPOTRA_COD' => 'PRIM',
                'TIPOTRA_NOMBRES' => 'Primario',
                'TIPOTRA_DETALLE' => 'Tratamiento primario de agua'
            ],
            [
                'TIPOTRA_COD' => 'SECU',
                'TIPOTRA_NOMBRES' => 'Secundario',
                'TIPOTRA_DETALLE' => 'Tratamiento secundario de agua'
            ],
            [
                'TIPOTRA_COD' => 'TERC',
                'TIPOTRA_NOMBRES' => 'Terciario',
                'TIPOTRA_DETALLE' => 'Tratamiento terciario de agua'
            ],
            [
                'TIPOTRA_COD' => 'AVAN',
                'TIPOTRA_NOMBRES' => 'Avanzado',
                'TIPOTRA_DETALLE' => 'Tratamiento avanzado de agua'
            ],
        ];

        foreach ($tipos as $tipo) {
            \App\Models\TipoTratamiento::create($tipo);
        }
    }
}
