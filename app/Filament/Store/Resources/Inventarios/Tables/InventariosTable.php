<?php

namespace App\Filament\Store\Resources\Inventarios\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;

use App\Models\Inventario;
use App\Models\MovimientoInventario;
use Filament\Actions\ActionGroup as ActionsActionGroup;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class InventariosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('pro_nombre')
                    ->label('Producto')
                    ->searchable(),

                TextColumn::make('inventario.inv_stock_actual')
                    ->label('Stock'),

                TextColumn::make('inventario.inv_stock_minimo')
                    ->label('Mínimo'),

                BadgeColumn::make('estado')
                    ->label('Estado')
                    ->getStateUsing(
                        fn($record) =>
                        $record->inventario?->inv_stock_actual <= $record->inventario?->inv_stock_minimo
                            ? 'Bajo'
                            : 'OK'
                    )
                    ->colors([
                        'danger' => 'Bajo',
                        'success' => 'OK',
                    ]),
            ])

            //  FILA ROJA SI ESTÁ BAJO
            ->recordClasses(
                fn($record) =>
                $record->inventario?->inv_stock_actual <= $record->inventario?->inv_stock_minimo
                    ? 'bg-red-100'
                    : null
            )

            ->recordActions([

                //  ACCIONES RÁPIDAS
                Action::make('quick_add')
                    ->label('')
                    ->icon('heroicon-o-plus')
                    ->color('success')
                    ->tooltip('Sumar 1')
                    ->action(function ($record) {

                        $inventario = $record->inventario ?? Inventario::create([
                            'inv_stock_actual' => 0,
                            'inv_stock_minimo' => 0,
                            'inv_actualizacion' => now(),
                            'inv_fk_producto' => $record->pro_id,
                        ]);

                        $inventario->increment('inv_stock_actual', 1);

                        MovimientoInventario::create([
                            'mov_tipo' => 'entrada',
                            'mov_cantidad' => 1,
                            'mov_fecha' => now(),
                            'mov_fk_producto' => $record->pro_id,
                        ]);
                    }),

                Action::make('quick_minus')
                    ->label('')
                    ->icon('heroicon-o-minus')
                    ->color('danger')
                    ->tooltip('Restar 1')
                    ->action(function ($record) {

                        $inventario = $record->inventario;

                        if (!$inventario) return;

                        $inventario->decrement('inv_stock_actual', 1);

                        MovimientoInventario::create([
                            'mov_tipo' => 'salida',
                            'mov_cantidad' => 1,
                            'mov_fecha' => now(),
                            'mov_fk_producto' => $record->pro_id,
                        ]);
                    }),

                //  GRUPO DE MOVIMIENTOS
                ActionsActionGroup::make([

                    Action::make('sumar_stock')
                        ->label('Agregar stock')
                        ->icon('heroicon-o-arrow-up')
                        ->color('success')
                        ->form([
                            TextInput::make('cantidad')
                                ->numeric()
                                ->required(),
                        ])
                        ->action(function ($record, array $data) {

                            $inventario = $record->inventario ?? Inventario::create([
                                'inv_stock_actual' => 0,
                                'inv_stock_minimo' => 0,
                                'inv_actualizacion' => now(),
                                'inv_fk_producto' => $record->pro_id,
                            ]);

                            $inventario->increment('inv_stock_actual', $data['cantidad']);

                            MovimientoInventario::create([
                                'mov_tipo' => 'entrada',
                                'mov_cantidad' => $data['cantidad'],
                                'mov_fecha' => now(),
                                'mov_fk_producto' => $record->pro_id,
                            ]);
                        }),

                    Action::make('restar_stock')
                        ->label('Restar stock')
                        ->icon('heroicon-o-arrow-down')
                        ->color('danger')
                        ->form([
                            TextInput::make('cantidad')
                                ->numeric()
                                ->required(),
                        ])
                        ->action(function ($record, array $data) {

                            $inventario = $record->inventario;

                            if (!$inventario) return;

                            $inventario->decrement('inv_stock_actual', $data['cantidad']);

                            MovimientoInventario::create([
                                'mov_tipo' => 'salida',
                                'mov_cantidad' => $data['cantidad'],
                                'mov_fecha' => now(),
                                'mov_fk_producto' => $record->pro_id,
                            ]);
                        }),

                ])
                    ->label('Movimientos')
                    ->icon('heroicon-o-arrows-up-down')
                    ->color('primary'),

                //  CONFIGURACIÓN
                Action::make('stock_minimo')
                    ->label('')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->color('warning')
                    ->tooltip('Configurar mínimo')
                    ->form([
                        TextInput::make('stock_minimo')
                            ->numeric()
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {

                        $inventario = $record->inventario ?? Inventario::create([
                            'inv_stock_actual' => 0,
                            'inv_stock_minimo' => 0,
                            'inv_actualizacion' => now(),
                            'inv_fk_producto' => $record->pro_id,
                        ]);

                        $inventario->update([
                            'inv_stock_minimo' => $data['stock_minimo'],
                            'inv_actualizacion' => now(),
                        ]);
                    }),


            ])

            ->toolbarActions([])
            ->filters([

                //  STOCK BAJO
                Filter::make('stock_bajo')
                    ->label('Stock bajo')
                    ->query(function (Builder $query) {
                        $query->whereHas('inventario', function ($q) {
                            $q->whereColumn('inv_stock_actual', '<=', 'inv_stock_minimo');
                        });
                    }),

                //  STOCK OK
                Filter::make('stock_ok')
                    ->label('Stock OK')
                    ->query(function (Builder $query) {
                        $query->whereHas('inventario', function ($q) {
                            $q->whereColumn('inv_stock_actual', '>', 'inv_stock_minimo');
                        });
                    }),

            ]);
    }
}
