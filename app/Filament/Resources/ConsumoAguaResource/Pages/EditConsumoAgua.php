<?php

namespace App\Filament\Resources\ConsumoAguaResource\Pages;

use App\Filament\Resources\ConsumoAguaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditConsumoAgua extends EditRecord
{
    protected static string $resource = ConsumoAguaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        if (isset($data['costos'])) {
            $costos = $data['costos'];
            $record->costos()->update([
                'COSTAG_VALORAGREGADO' => $costos['COSTAG_VALORAGREGADO'] ?? 0,
                'COSTENE_SUBTOTAL' => $costos['COSTENE_SUBTOTAL'] ?? 0,
                'COSTOAG_IVA' => $costos['COSTOAG_IVA'] ?? 0,
                'COSTOAG_TOTAL' => $costos['COSTOAG_TOTAL'] ?? 0,
            ]);
        }

        return $record;
    }
}
