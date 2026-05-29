<?php

namespace App\Filament\Store\Resources\Productos\Pages;

use App\Filament\Store\Resources\Productos\ProductoResource;
use App\Models\FotoProducto;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EditProducto extends EditRecord
{
    protected static string $resource = ProductoResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['foto_producto'] = $this->record->foto_principal;

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data) {
            $rutaFoto = Arr::pull($data, 'foto_producto');

            $rutaFoto = is_array($rutaFoto)
                ? ($rutaFoto[0] ?? null)
                : $rutaFoto;

            $record->update($data);

            if ($rutaFoto) {
                $fotoActual = $record->fotos()->first();

                if ($fotoActual) {
                    if ($fotoActual->fop_ruta && $fotoActual->fop_ruta !== $rutaFoto) {
                        Storage::disk('public')->delete($fotoActual->fop_ruta);
                    }

                    $fotoActual->update([
                        'fop_ruta' => $rutaFoto,
                    ]);
                } else {
                    FotoProducto::create([
                        'fop_ruta' => $rutaFoto,
                        'fop_fk_producto' => $record->pro_id,
                    ]);
                }
            }

            return $record;
        });
    }
}