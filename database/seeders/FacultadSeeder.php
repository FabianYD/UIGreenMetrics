<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacultadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facultades = [
            [
                'FACU_CODIGO' => 'FICA',
                'CAMPUS_ID' => 'CENT',
                'FACU_NOMBRE' => 'Facultad de Ingeniería'
            ],
            [
                'FACU_CODIGO' => 'FICAYA',
                'CAMPUS_ID' => 'CENT',
                'FACU_NOMBRE' => 'Facultad de Ciencias Agropecuarias'
            ],
            [
                'FACU_CODIGO' => 'FCCSS',
                'CAMPUS_ID' => 'CENT',
                'FACU_NOMBRE' => 'Facultad de Ciencias de la Salud'
            ],
            [
                'FACU_CODIGO' => 'FACAE',
                'CAMPUS_ID' => 'CENT',
                'FACU_NOMBRE' => 'Facultad de Ciencias Administrativas'
            ],
            [
                'FACU_CODIGO' => 'FECYT',
                'CAMPUS_ID' => 'CENT',
                'FACU_NOMBRE' => 'Facultad de Educación'
            ]
        ];

        foreach ($facultades as $facultad) {
            \App\Models\Facultad::updateOrCreate(
                ['FACU_CODIGO' => $facultad['FACU_CODIGO']],
                $facultad
            );
        }
    }
}
