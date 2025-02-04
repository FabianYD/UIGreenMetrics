<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoEnergiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\TipoEnergia::query()->delete();
        
        $tipos = [
            [
                'TIPOENE_ID' => 'ELEC',
                'TIPOENE_NOMBRES' => 'Eléctrica',
                'TIPOENE_DETALLE' => 'Energía eléctrica convencional'
            ],
            [
                'TIPOENE_ID' => 'SOLAR',
                'TIPOENE_NOMBRES' => 'Solar',
                'TIPOENE_DETALLE' => 'Energía solar fotovoltaica'
            ],
            [
                'TIPOENE_ID' => 'EOLIC',
                'TIPOENE_NOMBRES' => 'Eólica',
                'TIPOENE_DETALLE' => 'Energía eólica'
            ],
            [
                'TIPOENE_ID' => 'BIOM',
                'TIPOENE_NOMBRES' => 'Biomasa',
                'TIPOENE_DETALLE' => 'Energía de biomasa'
            ],
        ];

        foreach ($tipos as $tipo) {
            \App\Models\TipoEnergia::create($tipo);
        }
    }
}
