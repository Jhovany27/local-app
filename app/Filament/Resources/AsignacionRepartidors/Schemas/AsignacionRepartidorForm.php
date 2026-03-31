<?php

namespace App\Filament\Resources\AsignacionRepartidors\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AsignacionRepartidorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DateTimePicker::make('asr_fecha')
                    ->required(),
                TextInput::make('asr_estado')
                    ->required()
                    ->numeric(),
                TextInput::make('asr_fk_repartidor')
                    ->required()
                    ->numeric(),
                TextInput::make('asr_fk_pedido')
                    ->required()
                    ->numeric(),
            ]);
    }
}
