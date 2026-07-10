<?php

namespace App\Filament\Resources\Viajes\Tables;

use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ViajesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("asr_id")
                    ->label("#")
                    ->sortable(),

                TextColumn::make("repartidor.user.persona.per_nombre")
                    ->label("Repartidor")
                    ->formatStateUsing(function ($state, $record) {
                        $persona = $record->repartidor?->user?->persona;
                        if (!$persona) return $record->repartidor?->user?->email ?? "-";
                        return trim("{$persona->per_nombre} {$persona->per_paterno}");
                    })
                    ->searchable(query: function ($query, $search) {
                        $query->whereHas("repartidor.user", fn($q) => $q
                            ->where("email", "like", "%{$search}%")
                            ->orWhereHas("persona", fn($q2) => $q2
                                ->where("per_nombre", "like", "%{$search}%")
                                ->orWhere("per_paterno", "like", "%{$search}%")
                            )
                        );
                    }),

                TextColumn::make("repartidor.rep_tipo_vehiculo")
                    ->label("Vehiculo")
                    ->formatStateUsing(fn($state) => ucfirst($state ?? "-"))
                    ->badge()
                    ->color("gray"),

                TextColumn::make("pedido.ped_codigo")
                    ->label("Pedido")
                    ->weight("bold")
                    ->copyable(),

                TextColumn::make("pedido.tienda.tie_nombre")
                    ->label("Tienda")
                    ->searchable(),

                TextColumn::make("pedido.cliente.user.persona.per_nombre")
                    ->label("Cliente")
                    ->formatStateUsing(function ($state, $record) {
                        $persona = $record->pedido?->cliente?->user?->persona;
                        if (!$persona) return $record->pedido?->cliente?->user?->email ?? "-";
                        return trim("{$persona->per_nombre} {$persona->per_paterno}");
                    }),

                TextColumn::make("pedido.ped_total")
                    ->label("Total")
                    ->formatStateUsing(fn($state) => "$" . number_format($state ?? 0, 2))
                    ->sortable(),

                TextColumn::make("pedido.pago.pag_metodo_pago")
                    ->label("Pago")
                    ->badge()
                    ->color(fn($state) => strtolower($state ?? "") === "tarjeta" ? "success" : "warning")
                    ->default("-"),

                BadgeColumn::make("asr_estado")
                    ->label("Estado")
                    ->formatStateUsing(fn($state) => match((int)$state) {
                        -1 => "Cancelado",
                        0  => "En camino tienda",
                        1  => "En tienda",
                        2  => "En camino cliente",
                        3  => "Completado",
                        default => "-",
                    })
                    ->colors([
                        "danger"  => fn($state) => (int)$state === -1,
                        "warning" => fn($state) => in_array((int)$state, [0, 1]),
                        "info"    => fn($state) => (int)$state === 2,
                        "success" => fn($state) => (int)$state === 3,
                    ]),

                TextColumn::make("asr_fecha")
                    ->label("Fecha")
                    ->dateTime("d/m/Y H:i")
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make("asr_estado")
                    ->label("Estado")
                    ->options([
                        "-1" => "Cancelado",
                        "0"  => "En camino a tienda",
                        "1"  => "En tienda",
                        "2"  => "En camino al cliente",
                        "3"  => "Completado",
                    ]),
            ])
            ->defaultSort("asr_fecha", "desc");
    }
}