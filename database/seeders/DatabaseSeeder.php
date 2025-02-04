<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\ProvinciaSeeder;
use Database\Seeders\RolSeeder;
use Database\Seeders\TipoEnergiaSeeder;
use Database\Seeders\TipoTratamientoSeeder;
use Database\Seeders\TipoGeneracionEnergiaSeeder;
use Database\Seeders\UniversidadSeeder;
use Database\Seeders\FacultadSeeder;
use Database\Seeders\CampusSeeder;
use Database\Seeders\CiudadSeeder;
use Database\Seeders\UnidadMedidaAguaSeeder;
use Database\Seeders\UnidadMedidaEnergiaSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Primero las tablas independientes
        $this->call([
            RolSeeder::class,
            TipoEnergiaSeeder::class,
            TipoTratamientoSeeder::class,
            TipoGeneracionEnergiaSeeder::class,
            ProvinciaSeeder::class,
            UnidadMedidaAguaSeeder::class,
            UnidadMedidaEnergiaSeeder::class,
        ]);

        // Luego las tablas con dependencias
        $this->call([
            CiudadSeeder::class,
            UniversidadSeeder::class,
            CampusSeeder::class,
            FacultadSeeder::class,
        ]);

        // User::factory(10)->create();

        /* User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]); */

        DB::table('users')->insert([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
