<x-filament-panels::page>
    @php $p = $this->pedidoDetallado; @endphp

    @if(!$p)
        <p class="text-gray-500">Pedido no encontrado.</p>
    @else
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- ── COLUMNA IZQUIERDA ─────────────────────────── --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- INFO GENERAL --}}
            <x-filament::section heading="Información del pedido">
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 text-sm">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Código</p>
                        <p class="font-bold text-gray-900 dark:text-white mt-0.5">{{ $p->ped_codigo }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Fecha</p>
                        <p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $p->ped_fecha_pedido?->format('d/m/Y H:i') ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Tipo entrega</p>
                        <p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ strtolower($p->ped_tipo_entrega) === 'domicilio' ? 'Domicilio' : 'Recoger en tienda' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Estado</p>
                        @php
                            $color = match($p->ped_estado) {
                                'completado'     => 'bg-green-100 text-green-800',
                                'cancelado'      => 'bg-red-100 text-red-800',
                                'en_preparacion' => 'bg-blue-100 text-blue-800',
                                'listo'          => 'bg-purple-100 text-purple-800',
                                default          => 'bg-yellow-100 text-yellow-800',
                            };
                            $label = match($p->ped_estado) {
                                'pendiente'      => 'Pendiente',
                                'en_preparacion' => 'En preparación',
                                'listo'          => 'Listo',
                                'completado'     => 'Completado',
                                'cancelado'      => 'Cancelado',
                                default          => ucfirst($p->ped_estado),
                            };
                        @endphp
                        <span class="inline-block mt-0.5 px-2 py-0.5 rounded-full text-xs font-semibold {{ $color }}">{{ $label }}</span>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Método de pago</p>
                        <p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $p->pago?->pag_metodo_pago ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Estado pago</p>
                        <p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $p->pago?->pag_estado ?? '—' }}</p>
                    </div>
                </div>

                @if($p->ped_estado === 'cancelado' && $p->ped_motivo_cancelacion)
                <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <p class="text-xs font-semibold text-red-600 uppercase tracking-wide mb-1">
                        Cancelado por {{ $p->ped_cancelado_por === 'tienda' ? 'la tienda' : ($p->ped_cancelado_por === 'sistema' ? 'el sistema' : 'el cliente') }}
                    </p>
                    <p class="text-sm text-red-700 dark:text-red-300">{{ $p->ped_motivo_cancelacion }}</p>
                </div>
                @endif
            </x-filament::section>

            {{-- PRODUCTOS --}}
            <x-filament::section heading="Productos">
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($p->detalles as $detalle)
                    <div class="flex items-center justify-between py-2.5">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 rounded-full bg-amber-100 text-amber-700 text-xs font-bold flex items-center justify-center flex-shrink-0">
                                {{ $detalle->det_cantidad }}
                            </span>
                            <span class="text-sm text-gray-800 dark:text-gray-200">{{ $detalle->producto?->pro_nombre ?? '—' }}</span>
                        </div>
                        <div class="text-right text-sm">
                            <p class="font-semibold text-gray-900 dark:text-white">${{ number_format($detalle->det_subtotal, 2) }}</p>
                            <p class="text-xs text-gray-400">${{ number_format($detalle->det_precio_unitario, 2) }} c/u</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @php
                    $subtotalProductos = $p->detalles->sum('det_subtotal');
                    $pct      = \App\Models\ConfiguracionComision::porcentajeActual();
                    $comision = round($subtotalProductos * $pct / 100, 2);
                    $ganancia = round($subtotalProductos - $comision, 2);
                @endphp
                <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700 flex justify-between text-sm">
                    <span class="text-gray-500">Subtotal productos</span>
                    <span>${{ number_format($subtotalProductos, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm mt-1 text-amber-600 dark:text-amber-400">
                    <span>Comisión plataforma ({{ $pct }}%)</span>
                    <span>-${{ number_format($comision, 2) }}</span>
                </div>
                <div class="flex justify-between font-bold text-base mt-2 pt-2 border-t border-gray-200 dark:border-gray-700 text-green-700 dark:text-green-400">
                    <span>Ganancia tienda</span>
                    <span>${{ number_format($ganancia, 2) }}</span>
                </div>
            </x-filament::section>

            {{-- HISTORIAL DE ESTADOS --}}
            @if($p->estados->count())
            <x-filament::section heading="Historial de estados">
                <ol class="space-y-2">
                    @foreach($p->estados->sortBy('esp_fecha_cambio') as $estado)
                    <li class="flex items-center gap-3 text-sm">
                        <span class="w-2 h-2 rounded-full bg-amber-400 flex-shrink-0"></span>
                        <span class="text-gray-700 dark:text-gray-300 capitalize">{{ str_replace('_', ' ', $estado->esp_nombre) }}</span>
                        <span class="ml-auto text-xs text-gray-400">{{ \Carbon\Carbon::parse($estado->esp_fecha_cambio)->format('d/m/Y H:i') }}</span>
                    </li>
                    @endforeach
                </ol>
            </x-filament::section>
            @endif
        </div>

        {{-- ── COLUMNA DERECHA ───────────────────────────── --}}
        <div class="space-y-6">

            {{-- CLIENTE --}}
            <x-filament::section heading="Cliente">
                @php $persona = $p->cliente?->user?->persona; @endphp
                <div class="space-y-2 text-sm">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Nombre</p>
                        <p class="font-semibold text-gray-900 dark:text-white mt-0.5">
                            {{ $persona ? trim("{$persona->per_nombre} {$persona->per_paterno} {$persona->per_materno}") : '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Correo</p>
                        <p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $p->cliente?->user?->email ?? '—' }}</p>
                    </div>
                    @if($p->direccion)
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Dirección entrega</p>
                        <p class="text-gray-700 dark:text-gray-300 mt-0.5">
                            {{ $p->direccion->drc_calle }} {{ $p->direccion->drc_num_ext }},
                            {{ $p->direccion->drc_colonia }}, {{ $p->direccion->drc_ciudad }}
                        </p>
                    </div>
                    @endif
                </div>
            </x-filament::section>

            {{-- TIENDA --}}
            <x-filament::section heading="Tienda">
                <div class="space-y-2 text-sm">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Nombre</p>
                        <p class="font-semibold text-gray-900 dark:text-white mt-0.5">{{ $p->tienda?->tie_nombre ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Dirección</p>
                        <p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $p->tienda?->tie_direccion ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Teléfono</p>
                        <p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $p->tienda?->tie_telefono ?? '—' }}</p>
                    </div>
                </div>
            </x-filament::section>

            {{-- REPARTIDOR --}}
            <x-filament::section heading="Repartidor">
                @php
                    $rep     = $p->asignacion?->repartidor;
                    $repPer  = $rep?->user?->persona;
                    $asr     = $p->asignacion;
                @endphp
                @if($rep)
                <div class="space-y-2 text-sm">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Nombre</p>
                        <p class="font-semibold text-gray-900 dark:text-white mt-0.5">
                            {{ $repPer ? trim("{$repPer->per_nombre} {$repPer->per_paterno}") : $rep->user?->email ?? '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Vehículo</p>
                        <p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ ucfirst($rep->rep_tipo_vehiculo ?? '—') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Estado asignación</p>
                        @php
                            $aLabel = match((int)($asr->asr_estado ?? -99)) {
                                -1 => 'Cancelada',
                                0  => 'En camino a tienda',
                                1  => 'En tienda',
                                2  => 'En camino al cliente',
                                3  => 'Completada',
                                default => '—',
                            };
                            $aColor = match((int)($asr->asr_estado ?? -99)) {
                                3  => 'bg-green-100 text-green-800',
                                -1 => 'bg-red-100 text-red-800',
                                default => 'bg-blue-100 text-blue-800',
                            };
                        @endphp
                        <span class="inline-block mt-0.5 px-2 py-0.5 rounded-full text-xs font-semibold {{ $aColor }}">{{ $aLabel }}</span>
                    </div>
                    @if($asr && $asr->asr_motivo_cancelacion)
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Motivo cancelación</p>
                        <p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $asr->asr_motivo_cancelacion }}</p>
                    </div>
                    @endif
                </div>
                @else
                <p class="text-sm text-gray-400">Sin repartidor asignado.</p>
                @endif
            </x-filament::section>

        </div>
    </div>
    @endif
</x-filament-panels::page>
