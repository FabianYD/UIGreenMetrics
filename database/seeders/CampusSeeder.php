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
                'CAMPUS_NOMBRES' => 'Principal-Olivio',
                'CAMPUS_CALLEPRINCIPAL' => 'Av. 17 de Julio',
                'CAMPUS_CALLESECUNDARIA' => 'Gral. José María Córdova'
            ],
            [
                'CAMPUS_ID' => 'ANTIH',
                'UNI_ID' => 'UTN',
                'CAMPUS_NOMBRES' => 'An.Hospital-San vicente de paul', ,
                'CAMPUS_CALLEPRINCIPAL' => 'Juan Montalvo',
                'CAMPUS_CALLESECUNDARIA' => 'Juan de Velasco'
            ],
            [
                'CAMPUS_ID' => 'ANTIC',
                'UNI_ID' => 'UTN',
                'CAMPUS_NOMBRES' => 'Convento-Las carmelitas descalzas',
                'CAMPUS_CALLEPRINCIPAL' => 'Juan Montalvo',
                'CAMPUS_CALLESECUNDARIA' => 'Juan Velasco'
            ],
            [
                'CAMPUS_ID' => 'UEUTN',
                'UNI_ID' => 'UTN',
                'CAMPUS_NOMBRES' => 'Colegio Universitario',
                'CAMPUS_CALLEPRINCIPAL' => 'Luis Ulpiano de la Torre',
                'CAMPUS_CALLESECUNDARIA' => 'S/N'
            ],
            [
                'CAMPUS_ID' => 'CINF',
                'UNI_ID' => 'UTN',
                'CAMPUS_NOMBRES' => 'Centro Infantil Chispitas de Ternura',
                'CAMPUS_CALLEPRINCIPAL' => 'Carlos Barahona',
                'CAMPUS_CALLESECUNDARIA' => 'Av. Aurelio Espinosa Polit'
            ],

            
            [
                'CAMPUS_ID' => 'AZAY',
                'UNI_ID' => 'UTN',
                'CAMPUS_NOMBRES' => 'campus azaya',
                'CAMPUS_CALLEPRINCIPAL' => '13  de abril',
                'CAMPUS_CALLESECUNDARIA' => 'Morona Santiago'
            ],
            [
                'CAMPUS_ID' => 'PRAD',
                'UNI_ID' => 'UTN',
                'CAMPUS_NOMBRES' => '   Granja-La Pradera',
                'CAMPUS_CALLEPRINCIPAL' => 'Vía Principal',
                'CAMPUS_CALLESECUNDARIA' => 'San José de Chaltura'
            ],
            [
                'CAMPUS_ID' => 'HSM',
                'UNI_ID' => 'UTN',
                'CAMPUS_NOMBRES' => 'Hacienda-Santa Mónica',
                'CAMPUS_CALLEPRINCIPAL' => 'Vía Principal',
                'CAMPUS_CALLESECUNDARIA' => 'Sector ilumán'
            ],

            [
                'CAMPUS_ID' => 'GFAV',
                'UNI_ID' => 'UTN',
                'CAMPUS_NOMBRES' => 'Granja-La Favorita',
                'CAMPUS_CALLEPRINCIPAL' => 'ALLURIQUIN',
                'CAMPUS_CALLESECUNDARIA' => 'S/N'
            ],

            [
                'CAMPUS_ID' => 'YUYU',
                'UNI_ID' => 'UTN',
                'CAMPUS_NOMBRES' => 'Granja-Yuyucocha',
                'CAMPUS_CALLEPRINCIPAL' => 'Av. Capitán Espinoza de los Monteros',
                'CAMPUS_CALLESECUNDARIA' => 'Ciudadela Municipal Yuyucocha'
            ],

            [
                'CAMPUS_ID' => 'RESER',
                'UNI_ID' => 'UTN',
                'CAMPUS_NOMBRES' => 'Reseva-El cristal-',
                'CAMPUS_CALLEPRINCIPAL' => 'Imbabura',
                'CAMPUS_CALLESECUNDARIA' => 'S/N'
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
