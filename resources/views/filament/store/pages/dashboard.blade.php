<x-filament-panels::page>

    {{-- ── TARJETAS DE RESUMEN ─────────────────────────────────── --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">

        {{-- Ventas hoy --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm flex flex-col gap-1">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Ventas hoy</span>
                <span class="w-8 h-8 rounded-full bg-lime-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-lime-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-800">${{ number_format($ventasHoy, 2) }}</p>
            <p class="text-xs text-gray-400">Este mes: ${{ number_format($ventasMes, 2) }}</p>
        </div>

        {{-- Pedidos pendientes --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm flex flex-col gap-1">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Pendientes</span>
                <span class="w-8 h-8 rounded-full bg-amber-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $pedidosPendientes }}</p>
            <p class="text-xs text-gray-400">
                @if($pedidosPendientes > 0)
                    <span class="text-amber-500 font-semibold">{{ $pedidosPendientes }} esperando respuesta</span>
                @else
                    Sin pedidos nuevos
                @endif
            </p>
        </div>

        {{-- En preparación --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm flex flex-col gap-1">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">En preparación</span>
                <span class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $pedidosEnPrep }}</p>
            <p class="text-xs text-gray-400">pedidos en proceso</p>
        </div>

        {{-- Stock bajo --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm flex flex-col gap-1">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Stock bajo</span>
                <span class="w-8 h-8 rounded-full {{ $stockBajoCount > 0 ? 'bg-red-50' : 'bg-green-50' }} flex items-center justify-center">
                    <svg class="w-4 h-4 {{ $stockBajoCount > 0 ? 'text-red-500' : 'text-green-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold {{ $stockBajoCount > 0 ? 'text-red-600' : 'text-gray-800' }}">{{ $stockBajoCount }}</p>
            <p class="text-xs">
                @if($stockBajoCount > 0)
                    <span class="text-red-500 font-semibold">productos por reabastecer</span>
                @else
                    <span class="text-green-500">Todo en orden</span>
                @endif
            </p>
        </div>

    </div>

    {{-- ── GRÁFICA DE VENTAS (últimos 7 días) ─────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-gray-700">Ventas — últimos 7 días</h3>
                <p class="text-xs text-gray-400">Total en pesos por día</p>
            </div>
            <span class="text-xs bg-lime-50 text-lime-700 font-semibold px-2 py-1 rounded-full">Esta semana</span>
        </div>
        <div style="height: 220px; position: relative;">
            <canvas id="ventasChart"></canvas>
        </div>
    </div>

    {{-- ── FILA: ALERTAS DE STOCK + PEDIDOS ACTIVOS ────────────── --}}
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

        {{-- Alertas de stock --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50">
                <h3 class="text-sm font-bold text-gray-700">Alertas de stock</h3>
                @if($alertasStock->isEmpty())
                    <span class="text-xs bg-green-50 text-green-600 font-semibold px-2 py-1 rounded-full">Todo OK</span>
                @else
                    <span class="text-xs bg-red-50 text-red-600 font-semibold px-2 py-1 rounded-full">{{ $alertasStock->count() }} productos</span>
                @endif
            </div>

            @if($alertasStock->isEmpty())
                <div class="flex flex-col items-center justify-center py-10 text-gray-300">
                    <svg class="w-10 h-10 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <p class="text-sm font-medium text-gray-400">Sin alertas de stock</p>
                </div>
            @else
                <div class="divide-y divide-gray-50 max-h-72 overflow-y-auto">
                    @foreach($alertasStock as $inv)
                        @php
                            $pct = $inv->inv_stock_minimo > 0
                                ? min(100, round(($inv->inv_stock_actual / $inv->inv_stock_minimo) * 100))
                                : 0;
                            $critico = $inv->inv_stock_actual == 0;
                        @endphp
                        <div class="flex items-center gap-3 px-5 py-3">
                            <div class="w-8 h-8 rounded-lg {{ $critico ? 'bg-red-100' : 'bg-amber-50' }} flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 {{ $critico ? 'text-red-500' : 'text-amber-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-700 truncate">{{ $inv->producto->pro_nombre }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <div class="flex-1 bg-gray-100 rounded-full h-1.5">
                                        <div class="h-1.5 rounded-full {{ $critico ? 'bg-red-500' : 'bg-amber-400' }}" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-400 whitespace-nowrap">
                                        {{ $inv->inv_stock_actual }} / {{ $inv->inv_stock_minimo }}
                                    </span>
                                </div>
                            </div>
                            @if($critico)
                                <span class="text-xs bg-red-100 text-red-600 font-bold px-2 py-0.5 rounded-full flex-shrink-0">Agotado</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Pedidos activos --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50">
                <h3 class="text-sm font-bold text-gray-700">Pedidos activos</h3>
                @php $totalActivos = $pedidosPendientes + $pedidosEnPrep; @endphp
                @if($totalActivos > 0)
                    <span class="text-xs bg-amber-50 text-amber-600 font-semibold px-2 py-1 rounded-full">{{ $totalActivos }} activos</span>
                @else
                    <span class="text-xs bg-gray-50 text-gray-400 font-semibold px-2 py-1 rounded-full">Sin pedidos</span>
                @endif
            </div>

            @if($pedidosActivos->isEmpty())
                <div class="flex flex-col items-center justify-center py-10">
                    <svg class="w-10 h-10 mb-2 text-gray-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <p class="text-sm font-medium text-gray-400">Sin pedidos activos</p>
                </div>
            @else
                <div class="divide-y divide-gray-50 max-h-72 overflow-y-auto">
                    @foreach($pedidosActivos as $pedido)
                        @php
                            $esPendiente = $pedido->ped_estado === 'pendiente';
                            $nombreCliente = $pedido->cliente?->user?->name ?? 'Cliente';
                        @endphp
                        <div class="flex items-center gap-3 px-5 py-3">
                            <div class="w-8 h-8 rounded-lg {{ $esPendiente ? 'bg-amber-50' : 'bg-blue-50' }} flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 {{ $esPendiente ? 'text-amber-500' : 'text-blue-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-semibold text-gray-700">#{{ $pedido->ped_codigo }}</p>
                                    <span class="text-sm font-bold text-gray-700">${{ number_format($pedido->ped_total, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between mt-0.5">
                                    <p class="text-xs text-gray-400 truncate">{{ $nombreCliente }} · {{ $pedido->detalles->count() }} prod.</p>
                                    <span class="text-xs {{ $esPendiente ? 'text-amber-500' : 'text-blue-500' }} font-semibold ml-2 whitespace-nowrap">
                                        {{ $esPendiente ? 'Pendiente' : 'En prep.' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="px-5 py-3 border-t border-gray-50">
                    <a href="{{ \App\Filament\Store\Pages\Pedidos::getUrl() }}"
                       class="text-xs text-lime-600 font-semibold hover:underline">
                        Ver todos los pedidos →
                    </a>
                </div>
            @endif
        </div>

    </div>

    {{-- ── CHART.JS ─────────────────────────────────────────────── --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ventasData = @json($ventasSemana);
            const labels  = ventasData.map(d => d.label);
            const totales = ventasData.map(d => d.total);
            const maxVal  = Math.max(...totales, 1);

            const ctx = document.getElementById('ventasChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Ventas ($)',
                        data: totales,
                        backgroundColor: totales.map((v, i) =>
                            i === totales.length - 1 ? '#84cc16' : '#d9f99d'
                        ),
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => '$' + ctx.parsed.y.toLocaleString('es-MX', { minimumFractionDigits: 2 })
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 }, color: '#9ca3af' }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f3f4f6' },
                            ticks: {
                                font: { size: 11 },
                                color: '#9ca3af',
                                callback: v => '$' + v.toLocaleString('es-MX')
                            },
                            suggestedMax: maxVal * 1.2
                        }
                    }
                }
            });
        });
    </script>

</x-filament-panels::page>
