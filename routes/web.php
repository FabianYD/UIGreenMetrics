<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WaterCollectionPointController;

Route::get('/', [WaterCollectionPointController::class, 'index']);

Route::resource('water-points', WaterCollectionPointController::class);
