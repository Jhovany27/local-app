<?php

namespace App\Filament\Resources\Pagos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PagoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Pago')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('pag_fecha')
                            ->label('Fecha y hora')
                            ->dateTime('d/m/Y H:i'),

                        TextEntry::make('pag_monto')
                            ->label('Monto')
                            ->money('MXN'),

                        TextEntry::make('pag_metodo_pago')
                            ->label('Método de pago')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => match (strtolower((string) $state)) {
                                'tarjeta'  => 'Tarjeta',
                                'efectivo' => 'Efectivo',
                                default    => $state ?? '—',
                            })
                            ->color(fn (?string $state): string => match (strtolower((string) $state)) {
                                'tarjeta'  => 'info',
                                'efectivo' => 'success',
                                default    => 'gray',
                            }),

                        TextEntry::make('pag_estado')
                            ->label('Estado del pago')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Aceptado'   => 'success',
                                'En proceso' => 'warning',
                                'Rechazado'  => 'danger',
                                default      => 'gray',
                            }),

                        TextEntry::make('pag_stripe_payment_intent')
                            ->label('Stripe Payment Intent')
                            ->placeholder('N/A (pago en efectivo)')
                            ->columnSpan(2),

                        TextEntry::make('pag_stripe_charge_id')
                            ->label('Stripe Charge ID')
                            ->placeholder('N/A (pago en efectivo)')
                            ->columnSpan(3),
                    ]),

                Section::make('Cliente')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('pedido.cliente.user.name')
                            ->label('Nombre'),

                        TextEntry::make('pedido.cliente.user.email')
                            ->label('Correo electrónico'),
                    ]),

                Section::make('Tienda')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('pedido.tienda.tie_nombre')
                            ->label('Nombre de la tienda'),

                        TextEntry::make('pedido.tienda.tie_direccion')
                            ->label('Dirección'),
                    ]),

                Section::make('Pedido')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('pedido.ped_codigo')
                            ->label('Código del pedido'),

                        TextEntry::make('pedido.ped_fecha_pedido')
                            ->label('Fecha del pedido')
                            ->dateTime('d/m/Y H:i'),

                        TextEntry::make('pedido.ped_total')
                            ->label('Total del pedido')
                            ->money('MXN'),

                        TextEntry::make('pedido.ped_tipo_entrega')
                            ->label('Tipo de entrega')
                            ->badge(),

                        TextEntry::make('pedido.ped_estado')
                            ->label('Estado del pedido')
                            ->badge()
                            ->color(fn (?string $state): string => match ($state) {
                                'Completado' => 'success',
                                'Cancelado'  => 'danger',
                                'En camino'  => 'info',
                                'Pendiente'  => 'warning',
                                default      => 'gray',
                            })
                            ->placeholder('Sin estado'),
                    ]),
            ]);
    }
}
