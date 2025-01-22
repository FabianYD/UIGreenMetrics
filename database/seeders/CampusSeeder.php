<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campus = [
            [
                'CAMPUS_ID' => 'CENT',
                'UNI_ID' => 'UTN',
                'CAMPUS_NOMBRES' => 'Campus Universitario',
                'CAMPUS_CALLEPRINCIPAL' => 'Av. 17 de Julio',
                'CAMPUS_CALLESECUNDARIA' => 'Gral. José María Córdova'
            ],
            [
                'CAMPUS_ID' => 'ANTI',
                'UNI_ID' => 'UTN',
                'CAMPUS_NOMBRES' => 'Hospital San Vicente de Paúl',
                'CAMPUS_CALLEPRINCIPAL' => 'Juan Montalvo',
                'CAMPUS_CALLESECUNDARIA' => 'Luis Vargas Torres'
            ],
            [
                'CAMPUS_ID' => 'PRAD',
                'UNI_ID' => 'UTN',
                'CAMPUS_NOMBRES' => 'Granja Experimental La Pradera',
                'CAMPUS_CALLEPRINCIPAL' => 'Vía Principal',
                'CAMPUS_CALLESECUNDARIA' => 'San José de Chaltura'
            ],
            [
                'CAMPUS_ID' => 'YUYU',
                'UNI_ID' => 'UTN',
                'CAMPUS_NOMBRES' => 'Granja Experimental Yuyucocha',
                'CAMPUS_CALLEPRINCIPAL' => 'Av. Capitán Cristóbal de Troya',
                'CAMPUS_CALLESECUNDARIA' => 'Ciudadela Municipal Yuyucocha'
            ]
        ];

        foreach ($campus as $item) {
            \App\Models\Campus::updateOrCreate(
                ['CAMPUS_ID' => $item['CAMPUS_ID']],
                $item
            );
        }
    }
}
