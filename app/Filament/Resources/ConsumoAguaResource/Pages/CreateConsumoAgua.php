<?php

namespace App\Filament\Resources\ConsumoAguaResource\Pages;

use App\Filament\Resources\ConsumoAguaResource;
use App\Models\CostoAgua;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateConsumoAgua extends CreateRecord
{
    protected static string $resource = ConsumoAguaResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Crear el consumo de agua
        $consumo = static::getModel()::create($data);

        // Crear el registro de costos con los valores del formulario o valores por defecto
        if (isset($data['costos'])) {
            $costos = $data['costos'];
            $consumo->costos()->create([
                'COSTAG_VALORAGREGADO' => $costos['COSTAG_VALORAGREGADO'] ?? 0,
                'COSTENE_SUBTOTAL' => $costos['COSTENE_SUBTOTAL'] ?? 0,
                'COSTOAG_IVA' => $costos['COSTOAG_IVA'] ?? 0,
                'COSTOAG_TOTAL' => $costos['COSTOAG_TOTAL'] ?? 0,
            ]);
        } else {
            // Si no hay datos de costos, crear con valores por defecto
            $consumo->costos()->create([
                'COSTAG_VALORAGREGADO' => 0,
                'COSTENE_SUBTOTAL' => 0,
                'COSTOAG_IVA' => 0,
                'COSTOAG_TOTAL' => 0,
            ]);
        }

        return $consumo;
    }
}
