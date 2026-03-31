<?php

namespace App\Filament\Resources\Productos\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProductoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('pro_codigo')
                    ->required(),
                TextInput::make('pro_nombre')
                    ->required(),
                TextInput::make('pro_marca')
                    ->required(),
                Textarea::make('pro_detalles')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('pro_precio_prove')
                    ->required()
                    ->numeric(),
                TextInput::make('pro_precio_venta')
                    ->required()
                    ->numeric(),
                Select::make('pro_estado')
                    ->label('Estado')
                    ->options([
                        1 => 'Activo',
                        0 => 'Inactivo',
                    ])
                    ->required(),
                Select::make('pro_fk_tienda')
                    ->label('Tienda')
                    ->relationship(
                        name: 'tienda',
                        titleAttribute: 'tie_nombre',
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('pro_fk_categoria')
                    ->label('Categoría')
                    ->relationship(
                        name: 'categoria_producto',
                        titleAttribute: 'cat_nombre',
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }
}
