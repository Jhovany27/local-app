<?php

namespace App\Filament\Pages;

use App\Models\ConfiguracionComision;
use App\Services\RepartidorDeudaService;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ConfiguracionPlataformaPage extends Page
{
    protected string $view = 'filament.pages.configuracion-plataforma';

    protected static string|\UnitEnum|null $navigationGroup = 'Finanzas';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static ?string $navigationLabel = 'Configuración de plataforma';

    protected static ?string $title = 'Configuración de plataforma';

    protected static ?int $navigationSort = 25;

    public ?array $data = [];

    public function mount(): void
    {
        $config = ConfiguracionComision::activa();

        $this->form->fill([
            'com_porcentaje'              => $config?->com_porcentaje              ?? 10.00,
            'limite_deuda'                => $config?->limite_deuda                ?? 500.00,
            'frecuencia_liquidacion_dias' => $config?->frecuencia_liquidacion_dias ?? 7,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Comisión de la plataforma')
                    ->description('Porcentaje que cobra la plataforma sobre el valor de los productos (no incluye el costo de envío).')
                    ->schema([
                        TextInput::make('com_porcentaje')
                            ->label('Comisión (%)')
                            ->helperText('Ejemplo: 10 = 10% sobre el subtotal de productos.')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(0.5)
                            ->suffix('%')
                            ->required(),
                    ]),

                Section::make('Límite de deuda de repartidores')
                    ->description('Si la deuda acumulada de un repartidor supera este monto, se le bloquea de aceptar nuevos pedidos en efectivo hasta que liquide.')
                    ->schema([
                        TextInput::make('limite_deuda')
                            ->label('Límite de deuda (MXN)')
                            ->helperText('El repartidor recibirá una advertencia al alcanzar el 80% de este límite.')
                            ->numeric()
                            ->minValue(0)
                            ->step(10)
                            ->prefix('$')
                            ->required(),
                    ]),

                Section::make('Liquidaciones periódicas')
                    ->description('Frecuencia con la que se generan los cortes de pago a tiendas y repartidores.')
                    ->schema([
                        TextInput::make('frecuencia_liquidacion_dias')
                            ->label('Frecuencia del corte (días)')
                            ->helperText('7 = semanal · 14 = quincenal · 30 = mensual')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(90)
                            ->suffix('días')
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public function guardar(): void
    {
        $datos = $this->form->getState();

        $config = ConfiguracionComision::activa();

        if ($config) {
            $config->update([
                'com_porcentaje'              => $datos['com_porcentaje'],
                'limite_deuda'                => $datos['limite_deuda'],
                'frecuencia_liquidacion_dias' => $datos['frecuencia_liquidacion_dias'],
            ]);
        } else {
            ConfiguracionComision::create([
                'com_porcentaje'              => $datos['com_porcentaje'],
                'limite_deuda'                => $datos['limite_deuda'],
                'frecuencia_liquidacion_dias' => $datos['frecuencia_liquidacion_dias'],
                'com_activa'                  => true,
                'com_fecha'                   => now(),
            ]);
        }

        Notification::make()
            ->title('Configuración actualizada')
            ->success()
            ->send();
    }

    public function getRepartidoresBloqueadosProperty()
    {
        return \App\Models\Repartidor::with('user.persona')
            ->get()
            ->filter(fn($r) => RepartidorDeudaService::superaLimite($r))
            ->values();
    }
}
