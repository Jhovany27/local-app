<?php

namespace App\Filament\Store\Resources\Productos\Pages;

use App\Filament\Store\Resources\Productos\ProductoResource;
use App\Models\FotoProducto;
use App\Models\Producto;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Models\Inventario;
use App\Models\MovimientoInventario;

class CreateProducto extends CreateRecord
{
    protected static string $resource = ProductoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $tiendaId = session('store_tienda_id');

        if (! $tiendaId) {
            abort(403, 'No hay una tienda seleccionada.');
        }

        $data['pro_fk_tienda'] = $tiendaId;

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $rutaFoto = Arr::pull($data, 'foto_producto');

            $rutaFoto = is_array($rutaFoto)
                ? ($rutaFoto[0] ?? null)
                : $rutaFoto;

            $producto = Producto::create($data);

            if ($rutaFoto) {
                FotoProducto::create([
                    'fop_ruta' => $rutaFoto,
                    'fop_fk_producto' => $producto->pro_id,
                ]);
            }

            return $producto;
        });
    }

    protected function afterCreate(): void
    {
        $producto = $this->record;

        $stockInicial = $this->data['stock_inicial'];
        $stockMinimo = $this->data['stock_minimo'];

        // CREAR INVENTARIO
        Inventario::create([
            'inv_stock_actual' => $stockInicial,
            'inv_stock_minimo' => $stockMinimo,
            'inv_actualizacion' => now(),
            'inv_fk_producto' => $producto->pro_id,
        ]);

        // REGISTRAR MOVIMIENTO INICIAL
        MovimientoInventario::create([
            'mov_tipo' => 'entrada',
            'mov_cantidad' => $stockInicial,
            'mov_fecha' => now(),
            'mov_fk_producto' => $producto->pro_id,
        ]);
    }
}
