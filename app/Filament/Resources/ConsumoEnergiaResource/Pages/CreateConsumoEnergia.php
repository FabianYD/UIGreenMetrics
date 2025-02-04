<?php

namespace App\Filament\Resources\ConsumoEnergiaResource\Pages;

use App\Filament\Resources\ConsumoEnergiaResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateConsumoEnergia extends CreateRecord
{
    protected static string $resource = ConsumoEnergiaResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Crear el consumo de energía
        $consumo = static::getModel()::create($data);

        // Crear el registro de costos con los valores del formulario o valores por defecto
        if (isset($data['costos'])) {
            $costos = $data['costos'];
            $consumo->costos()->create([
                'COSTENE_VALORCONS' => $costos['COSTENE_VALORCONS'] ?? 0,
                'COSTENE_SUBSIDIO' => $costos['COSTENE_SUBSIDIO'] ?? 0,
                'COSTENE_SUBTOTAL' => $costos['COSTENE_SUBTOTAL'] ?? 0,
                'COSTENE_SUBTOTAL_ALUM_PUBLIC' => $costos['COSTENE_SUBTOTAL_ALUM_PUBLIC'] ?? 0,
                'COSTENE_BASEIVA' => $costos['COSTENE_BASEIVA'] ?? 0,
                'COSTENE_TOTAL' => $costos['COSTENE_TOTAL'] ?? 0,
            ]);
        } else {
            // Si no hay datos de costos, crear con valores por defecto
            $consumo->costos()->create([
                'COSTENE_VALORCONS' => 0,
                'COSTENE_SUBSIDIO' => 0,
                'COSTENE_SUBTOTAL' => 0,
                'COSTENE_SUBTOTAL_ALUM_PUBLIC' => 0,
                'COSTENE_BASEIVA' => 0,
                'COSTENE_TOTAL' => 0,
            ]);
        }

        return $consumo;
    }
}
