<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoGeneracionEnergiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\TipoGeneracionEnergia::query()->delete();
        
        $tipos = [
            [
                'GENTYPE_DETALLE' => 'Cogeneración de Energía',
                'GENTYPE_VALOR' => 1000
            ],
            [
                'GENTYPE_DETALLE' => 'Energía Solar',
                'GENTYPE_VALOR' => 2000
            ],
            [
                'GENTYPE_DETALLE' => 'Energía Eólica',
                'GENTYPE_VALOR' => 1500
            ],
            [
                'GENTYPE_DETALLE' => 'Energía Hidroeléctrica',
                'GENTYPE_VALOR' => 3000
            ],
        ];

        foreach ($tipos as $tipo) {
            \App\Models\TipoGeneracionEnergia::create($tipo);
        }
    }
}
