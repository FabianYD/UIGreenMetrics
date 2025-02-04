<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provincias = [
            [
                'PROV_ID' => 'IMB',
                'PROV_NOMBRES' => 'Imbabura',
                'PROV_CODIGOSPOSTAL' => '100150'
            ],
            [
                'PROV_ID' => 'PIC',
                'PROV_NOMBRES' => 'Pichincha',
                'PROV_CODIGOSPOSTAL' => '170150'
            ],
            [
                'PROV_ID' => 'GUA',
                'PROV_NOMBRES' => 'Guayas',
                'PROV_CODIGOSPOSTAL' => '090150'
            ],
            [
                'PROV_ID' => 'AZU',
                'PROV_NOMBRES' => 'Azuay',
                'PROV_CODIGOSPOSTAL' => '010150'
            ],
        ];

        foreach ($provincias as $provincia) {
            \App\Models\Provincia::updateOrCreate(
                ['PROV_ID' => $provincia['PROV_ID']],
                $provincia
            );
        }
    }
}
