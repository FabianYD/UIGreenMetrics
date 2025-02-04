<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnidadMedidaEnergiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unidades = [
            [
                'MEDENE_COD' => 'KWH',
                'MEDENE_NOMBRE' => 'Kilowatt-hora'
            ],
            [
                'MEDENE_COD' => 'MWH',
                'MEDENE_NOMBRE' => 'Megawatt-hora'
            ],
            [
                'MEDENE_COD' => 'KVA',
                'MEDENE_NOMBRE' => 'Kilovoltio-amperio'
            ],
            [
                'MEDENE_COD' => 'KW',
                'MEDENE_NOMBRE' => 'Kilowatt'
            ]
        ];

        foreach ($unidades as $unidad) {
            \App\Models\UnidadMedidaEnergia::updateOrCreate(
                ['MEDENE_COD' => $unidad['MEDENE_COD']],
                $unidad
            );
        }
    }
}
