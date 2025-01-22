<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Rol::query()->delete();
        
        $roles = [
            [
                'ROL_COD' => 'ADM',
                'ROL_DETALLE' => 'Administrador'
            ],
            [
                'ROL_COD' => 'SUP',
                'ROL_DETALLE' => 'Supervisor'
            ],
            [
                'ROL_COD' => 'TEC',
                'ROL_DETALLE' => 'Técnico'
            ],
            [
                'ROL_COD' => 'OPE',
                'ROL_DETALLE' => 'Operador'
            ],
        ];

        foreach ($roles as $rol) {
            \App\Models\Rol::create($rol);
        }
    }
}
