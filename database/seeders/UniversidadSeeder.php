<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UniversidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $universidades = [
            [
                'UNI_ID' => 'UTN',
                'CIU_ID' => 'IBR',
                'UNI_NOMBRES' => 'Universidad Técnica del Norte'
            ]
        ];

        foreach ($universidades as $universidad) {
            \App\Models\Universidad::updateOrCreate(
                ['UNI_ID' => $universidad['UNI_ID']],
                $universidad
            );
        }
    }
}
