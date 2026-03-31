<?php

namespace App\Filament\Resources\Calificacions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CalificacionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('cal_puntuacion')
                    ->required()
                    ->numeric(),
                Textarea::make('cal_comentario')
                    ->required()
                    ->columnSpanFull(),
                DateTimePicker::make('cal_fecha')
                    ->required(),
                TextInput::make('cal_fk_tienda')
                    ->required()
                    ->numeric(),
                TextInput::make('cal_fk_cliente')
                    ->required()
                    ->numeric(),
            ]);
    }
}
