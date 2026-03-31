<?php

namespace App\Filament\Resources\FotoProductos\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;

class FotoProductoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('fop_ruta')
                    ->label('Foto del producto')
                    ->image()
                    ->disk('public')
                    ->directory('fotoproductos')
                    ->required(),
                Select::make('fop_fk_producto')
                    ->label('Producto')
                    ->relationship(
                        name: 'producto',
                        titleAttribute: 'pro_nombre',
                    )
            ]);
    }
}
