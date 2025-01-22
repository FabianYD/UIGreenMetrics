<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CiudadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ciudades = [
            [
                'CIU_ID' => 'IBR',
                'PROV_ID' => 'IMB',
                'CIU_NOMBRES' => 'Ibarra',
                'CIU_CODIGOSPOSTAL' => '100101'
            ]
        ];

        foreach ($ciudades as $ciudad) {
            \App\Models\Ciudad::updateOrCreate(
                ['CIU_ID' => $ciudad['CIU_ID']],
                $ciudad
            );
        }
    }
}
