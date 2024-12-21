<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WaterCollectionPointController;
use App\Http\Controllers\EnergyCollectionPointController;

// Rutas para los puntos de recolección de agua
Route::get('/water-points', [WaterCollectionPointController::class, 'index']) ->name('water-points.SostenibilidadAgua');

Route::get('/energy-points', [EnergyCollectionPointController::class, 'index']) ->name('energy-points.SostenibilidadEnergia');







/*Route::get('/water-points/create', [WaterCollectionPointController::class, 'create'])
    ->name('water-points.create');

Route::post('/water-points', [WaterCollectionPointController::class, 'store'])
    ->name('water-points.store');

Route::get('/water-points/{tratamiento}/edit', [WaterCollectionPointController::class, 'edit'])
    ->name('water-points.edit');
    

Route::put('/water-points/{tratamiento}', [WaterCollectionPointController::class, 'update'])
    ->name('water-points.update');

Route::delete('/water-points/{tratamiento}', [WaterCollectionPointController::class, 'destroy'])
    ->name('water-points.destroy');

// Ruta para calcular la sostenibilidad
Route::post('/water-points/calcular', [WaterCollectionPointController::class, 'calcularSostenibilidad'])
    ->name('water-points.calcular');*/