<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnidadMedidaAguaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unidades = [
            [
                'MEDIDADAG_COD' => 'M3',
                'MEDIDAAGU_NOMBRE' => 'Metros Cúbicos'
            ],
            [
                'MEDIDADAG_COD' => 'LT',
                'MEDIDAAGU_NOMBRE' => 'Litros'
            ],
            [
                'MEDIDADAG_COD' => 'GL',
                'MEDIDAAGU_NOMBRE' => 'Galones'
            ]
        ];

        foreach ($unidades as $unidad) {
            \App\Models\UnidadMedidaAgua::updateOrCreate(
                ['MEDIDADAG_COD' => $unidad['MEDIDADAG_COD']],
                $unidad
            );
        }
    }
}
