<?php

namespace App\Filament\Resources\Direccions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Field;

class DireccionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('drc_calle')
                    ->required(),
                TextInput::make('drc_numero')
                    ->required(),
                TextInput::make('drc_colonia')
                    ->required(),
                TextInput::make('drc_ciudad')
                    ->required(),
                TextInput::make('drc_estado')
                    ->required(),
                TextInput::make('drc_codigo_postal')
                    ->required()
                    ->numeric(),
                TextInput::make('drc_latitud')
                    ->hidden()
                    ->dehydrated()
                    ->live(),
                TextInput::make('drc_longitud')
                    ->hidden()
                    ->dehydrated()
                    ->live(),
                Field::make('mapa')
                    ->view('filament.maps.leaflet-map')
                    ->columnSpanFull(),
                Textarea::make('drc_referencias')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
