<?php

namespace App\Filament\Resources\Pedidos\Tables;

use App\Filament\Resources\Pedidos\PedidoResource;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PedidosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("ped_codigo")
                    ->label("Codigo")
                    ->searchable()
                    ->weight("bold")
                    ->copyable(),

                TextColumn::make("cliente.user.persona.per_nombre")
                    ->label("Cliente")
                    ->formatStateUsing(function ($state, $record) {
                        $persona = $record->cliente?->user?->persona;
                        if (!$persona) return $record->cliente?->user?->email ?? "-";
                        return trim("{$persona->per_nombre} {$persona->per_paterno}");
                    })
                    ->searchable(query: function ($query, $search) {
                        $query->whereHas("cliente.user", function ($q) use ($search) {
                            $q->where("email", "like", "%{$search}%")
                              ->orWhereHas("persona", fn($q2) => $q2
                                  ->where("per_nombre", "like", "%{$search}%")
                                  ->orWhere("per_paterno", "like", "%{$search}%")
                              );
                        });
                    }),

                TextColumn::make("tienda.tie_nombre")
                    ->label("Tienda")
                    ->searchable(),

                TextColumn::make("asignacion.repartidor.user.persona.per_nombre")
                    ->label("Repartidor")
                    ->formatStateUsing(function ($state, $record) {
                        $persona = $record->asignacion?->repartidor?->user?->persona;
                        if (!$persona) return "-";
                        return trim("{$persona->per_nombre} {$persona->per_paterno}");
                    })
                    ->default("-"),

                BadgeColumn::make("ped_estado")
                    ->label("Estado")
                    ->formatStateUsing(fn($state) => match($state) {
                        "pendiente"      => "Pendiente",
                        "en_preparacion" => "En preparacion",
                        "listo"          => "Listo",
                        "completado"     => "Completado",
                        "cancelado"      => "Cancelado",
                        default          => ucfirst($state),
                    })
                    ->colors([
                        "warning" => fn($state) => $state === "pendiente",
                        "info"    => fn($state) => $state === "en_preparacion",
                        "primary" => fn($state) => $state === "listo",
                        "success" => fn($state) => $state === "completado",
                        "danger"  => fn($state) => $state === "cancelado",
                    ]),

                BadgeColumn::make("pago.pag_metodo_pago")
                    ->label("Pago")
                    ->colors([
                        "success" => fn($state) => strtolower($state ?? "") === "tarjeta",
                        "warning" => fn($state) => strtolower($state ?? "") === "efectivo",
                    ])
                    ->default("-"),

                TextColumn::make("ped_total")
                    ->label("Total")
                    ->formatStateUsing(fn($state) => "$" . number_format($state, 2))
                    ->sortable(),

                TextColumn::make("ped_tipo_entrega")
                    ->label("Entrega")
                    ->formatStateUsing(fn($state) => $state === "domicilio" ? "Domicilio" : "Recoger")
                    ->badge()
                    ->color(fn($state) => $state === "domicilio" ? "info" : "gray"),

                TextColumn::make("ped_fecha_pedido")
                    ->label("Fecha")
                    ->dateTime("d/m/Y H:i")
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make("ped_estado")
                    ->label("Estado")
                    ->options([
                        "pendiente"      => "Pendiente",
                        "en_preparacion" => "En preparacion",
                        "listo"          => "Listo",
                        "completado"     => "Completado",
                        "cancelado"      => "Cancelado",
                    ]),
                SelectFilter::make("ped_tipo_entrega")
                    ->label("Tipo entrega")
                    ->options([
                        "domicilio" => "Domicilio",
                        "recoger"   => "Recoger en tienda",
                    ]),
            ])
            ->recordUrl(fn($record) => PedidoResource::getUrl("view", ["record" => $record->ped_id]))
            ->defaultSort("ped_fecha_pedido", "desc");
    }
}