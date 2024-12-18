<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PDO;

class CreateDatabase extends Command
{
    protected $signature = 'db:create';
    protected $description = 'Create the database if it does not exist';

    public function handle()
    {
        $database = env('DB_DATABASE', 'uigreenmetrics');
        $host = env('DB_HOST', '127.0.0.1');
        $port = env('DB_PORT', '5432');
        $username = env('DB_USERNAME', 'postgres');
        $password = env('DB_PASSWORD', '');

        try {
            // Conectar a la base de datos 'postgres'
            $pdo = new PDO("pgsql:host=$host;port=$port", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Verificar si la base de datos existe
            $stmt = $pdo->query("SELECT 1 FROM pg_database WHERE datname = '$database'");
            $exists = $stmt->fetchColumn();

            if (!$exists) {
                // Crear la base de datos si no existe
                $pdo->exec("CREATE DATABASE $database");
                $this->info("Database $database created successfully.");
            } else {
                $this->info("Database $database already exists.");
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
