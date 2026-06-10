<?php

namespace App\Filament\Pages;

use App\Models\ConfiguracionEnvio;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ConfiguracionEnvioPage extends Page
{
    protected string $view = 'filament.pages.configuracion-envio';
    protected static string|\UnitEnum|null $navigationGroup = 'Finanzas';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;
    protected static ?string $navigationLabel = 'Tarifas de Envío';
    protected static ?string $title = 'Configuración de Tarifas de Envío';
    protected static ?int $navigationSort = 2;

    public float $tarifa_base   = 15.00;
    public float $precio_por_km = 5.00;

    public function mount(): void
    {
        $config = ConfiguracionEnvio::actual();
        $this->tarifa_base   = $config->tarifa_base;
        $this->precio_por_km = $config->precio_por_km;

        $this->form->fill([
            'tarifa_base'   => $this->tarifa_base,
            'precio_por_km' => $this->precio_por_km,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tarifas de Envío del Repartidor')
                    ->description('Estos valores se usan para calcular el costo de envío de cada pedido según la distancia entre la tienda y el cliente.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('tarifa_base')
                            ->label('Tarifa base (MXN)')
                            ->helperText('Costo mínimo de envío independientemente de la distancia.')
                            ->numeric()
                            ->minValue(0)
                            ->step(0.50)
                            ->prefix('$')
                            ->required(),

                        TextInput::make('precio_por_km')
                            ->label('Precio por kilómetro (MXN)')
                            ->helperText('Costo adicional por cada kilómetro recorrido.')
                            ->numeric()
                            ->minValue(0)
                            ->step(0.50)
                            ->prefix('$')
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public ?array $data = [];

    public function guardar(): void
    {
        $datos = $this->form->getState();

        ConfiguracionEnvio::actual()->update([
            'tarifa_base'   => $datos['tarifa_base'],
            'precio_por_km' => $datos['precio_por_km'],
        ]);

        Notification::make()
            ->title('Tarifas actualizadas correctamente')
            ->success()
            ->send();
    }
}
