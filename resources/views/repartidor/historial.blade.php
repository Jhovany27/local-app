<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Historial de entregas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/repartidor/historial.css')
</head>

<body>
    <div class="app">

        <div class="header">
            <a href="{{ route('repartidor.index') }}" class="btn-back">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </a>
            <div class="header-logo"><img src="{{ asset('images/Logo_local_app.png') }}" alt="LocalApp"></div>
            <div style="width:22px"></div>
        </div>

        {{-- STATS HERO --}}
        <div class="stats-hero">
            <p class="stats-title">Mis entregas</p>
            <div class="stats-grid">
                <div class="stat-box">
                    <p class="stat-val">{{ $asignaciones->count() }}</p>
                    <p class="stat-label">Total entregas</p>
                </div>
                <div class="stat-box">
                    <p class="stat-val">
                        ${{ number_format($asignaciones->sum(fn($a) => $a->pedido?->ped_total ?? 0), 0) }}</p>
                    <p class="stat-label">Total entregado</p>
                </div>
                <div class="stat-box">
                    <p class="stat-val">{{ $asignaciones->filter(fn($a) => $a->asr_fecha?->isToday())->count() }}</p>
                    <p class="stat-label">Hoy</p>
                </div>
                <div class="stat-box">
                    <p class="stat-val">{{ $asignaciones->filter(fn($a) => $a->asr_fecha?->isCurrentWeek())->count() }}
                    </p>
                    <p class="stat-label">Esta semana</p>
                </div>
            </div>
        </div>

        <div class="body">

            {{-- FILTROS --}}
            <div class="filtros">
                <button class="filtro-btn active" onclick="filtrar('todo', this)">Todo</button>
                <button class="filtro-btn" onclick="filtrar('hoy', this)">Hoy</button>
                <button class="filtro-btn" onclick="filtrar('semana', this)">Esta semana</button>
                <button class="filtro-btn" onclick="filtrar('mes', this)">Este mes</button>
            </div>

            {{-- GRUPOS POR FECHA --}}
            @php
                $agrupadas = $asignaciones->groupBy(fn($a) => $a->asr_fecha?->format('d/m/Y') ?? '—');
            @endphp

            @if ($asignaciones->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25" />
                        </svg>
                    </div>
                    <p>Sin entregas aún</p>
                    <span>Tus entregas completadas aparecerán aquí</span>
                </div>
            @else
                @foreach ($agrupadas as $fecha => $grupo)
                    <div class="fecha-grupo" data-fecha="{{ $grupo->first()?->asr_fecha?->toDateString() }}">
                        <p class="fecha-grupo-label">
                            @php
                                $carbon = $grupo->first()?->asr_fecha;
                                if ($carbon?->isToday()) {
                                    echo 'Hoy';
                                } elseif ($carbon?->isYesterday()) {
                                    echo 'Ayer';
                                } else {
                                    echo $carbon?->translatedFormat('l d \d\e F') ?? $fecha;
                                }
                            @endphp
                            — {{ $grupo->count() }} entrega(s)
                        </p>

                        @foreach ($grupo as $asignacion)
                            @php $pedido = $asignacion->pedido; @endphp
                            <div class="entrega-card" data-fecha-iso="{{ $asignacion->asr_fecha?->toDateString() }}"
                                onclick="abrirDetalle('modal-{{ $asignacion->asr_id }}')">
                                <div class="entrega-header">
                                    <div>
                                        <p class="entrega-tienda">{{ $pedido?->tienda?->tie_nombre ?? '—' }}</p>
                                        <p class="entrega-fecha">{{ $asignacion->asr_fecha?->format('H:i') }}</p>
                                    </div>
                                    <p class="entrega-total">${{ number_format($pedido?->ped_total ?? 0, 2) }}</p>
                                </div>
                                <div class="entrega-body">
                                    @php $persona = $pedido?->cliente?->user?->persona; @endphp
                                    <p class="entrega-cliente">
                                        Cliente: <span>{{ $persona?->per_nombre }} {{ $persona?->per_paterno }}</span>
                                    </p>
                                    <span class="entrega-badge">Entregado</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @endif

        </div>

        <nav class="bottom-nav">
            <a href="{{ route('repartidor.index') }}" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25" />
                </svg>
            </a>
            <a href="{{ route('repartidor.historial') }}" class="nav-item active">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15" />
                </svg>
            </a>
            <a href="{{ route('repartidor.perfil') }}" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0" />
                </svg>
            </a>
        </nav>

    </div>

    {{-- MODALES DETALLE --}}
    @foreach ($asignaciones as $asignacion)
        @php
            $pedido = $asignacion->pedido;
            $persona = $pedido?->cliente?->user?->persona;
        @endphp
        <div class="modal-detalle" id="modal-{{ $asignacion->asr_id }}">
            <div class="modal-inner">

                <div class="modal-header">
                    <p class="modal-title">#{{ $pedido?->ped_codigo ?? 'Entrega' }}</p>
                    <button class="modal-close" onclick="cerrarDetalle('modal-{{ $asignacion->asr_id }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- INFO ENTREGA --}}
                <div class="modal-section">
                    <p class="modal-section-label">Información de la entrega</p>
                    <div class="modal-info-row">
                        <span class="modal-info-key">Tienda</span>
                        <span class="modal-info-val">{{ $pedido?->tienda?->tie_nombre ?? '—' }}</span>
                    </div>
                    <div class="modal-info-row">
                        <span class="modal-info-key">Cliente</span>
                        <span class="modal-info-val">{{ $persona?->per_nombre }} {{ $persona?->per_paterno }}</span>
                    </div>
                    <div class="modal-info-row">
                        <span class="modal-info-key">Teléfono cliente</span>
                        <span class="modal-info-val">{{ $persona?->per_telefono ?? '—' }}</span>
                    </div>
                    <div class="modal-info-row">
                        <span class="modal-info-key">Fecha de entrega</span>
                        <span class="modal-info-val">{{ $asignacion->asr_fecha?->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="modal-info-row">
                        <span class="modal-info-key">Método de pago</span>
                        <span class="modal-info-val"
                            style="color:{{ strtolower($pedido?->pago?->pag_metodo_pago) === 'tarjeta' ? '#1d4ed8' : '#4a8a06' }}">
                            {{ strtolower($pedido?->pago?->pag_metodo_pago) === 'tarjeta' ? 'Tarjeta' : 'Efectivo' }}
                        </span>
                    </div>
                </div>

                {{-- PRODUCTOS --}}
                <div class="modal-section" style="margin-top:1rem;">
                    <p class="modal-section-label">Productos entregados</p>
                    @foreach ($pedido?->detalles ?? [] as $det)
                        <div class="prod-row">
                            @if ($det->producto?->foto_principal)
                                <img src="{{ asset('storage/' . $det->producto->foto_principal) }}" class="prod-img">
                            @else
                                <div class="prod-img" style="background:#f0fde0;border-radius:0.5rem;"></div>
                            @endif
                            <div style="flex:1;">
                                <p class="prod-nombre">{{ $det->producto?->pro_nombre }}</p>
                                <p class="prod-info">×{{ $det->det_cantidad }} —
                                    ${{ number_format($det->det_precio_unitario, 2) }} c/u</p>
                            </div>
                            <span class="prod-precio">${{ number_format($det->det_subtotal, 2) }}</span>
                        </div>
                    @endforeach

                    {{-- Totales --}}
                    <div
                        style="margin-top:0.75rem;background:#f8fdf0;border:1px solid #e8f5d0;border-radius:0.75rem;padding:0.75rem;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:0.35rem;">
                            <span style="font-size:0.78rem;color:#888;">Subtotal productos</span>
                            <span
                                style="font-size:0.78rem;font-weight:700;color:#111;">${{ number_format($pedido?->detalles?->sum('det_subtotal') ?? 0, 2) }}</span>
                        </div>
                        @if (($pedido?->ped_costo_envio ?? 0) > 0)
                            <div style="display:flex;justify-content:space-between;margin-bottom:0.35rem;">
                                <span style="font-size:0.78rem;color:#888;">Costo de envío</span>
                                <span
                                    style="font-size:0.78rem;font-weight:700;color:#1d4ed8;">${{ number_format($pedido->ped_costo_envio, 2) }}</span>
                            </div>
                        @endif
                        <div
                            style="display:flex;justify-content:space-between;border-top:1px solid #e8f5d0;padding-top:0.35rem;">
                            <span style="font-size:0.85rem;font-weight:800;color:#111;">Total</span>
                            <span
                                style="font-size:1rem;font-weight:900;color:#4a8a06;">${{ number_format($pedido?->ped_total ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endforeach

    <script>
        function abrirDetalle(id) {
            document.getElementById(id).classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function cerrarDetalle(id) {
            document.getElementById(id).classList.remove('open');
            document.body.style.overflow = '';
        }

        document.querySelectorAll('.modal-detalle').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('open');
                    document.body.style.overflow = '';
                }
            });
        });

        // ── FILTROS ───────────────────────────────────────────
        function filtrar(periodo, btn) {
            // Actualizar botón activo
            document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const hoy = new Date();
            hoy.setHours(0, 0, 0, 0);

            const inicioSemana = new Date(hoy);
            inicioSemana.setDate(hoy.getDate() - hoy.getDay());

            const inicioMes = new Date(hoy.getFullYear(), hoy.getMonth(), 1);

            document.querySelectorAll('.fecha-grupo').forEach(grupo => {
                const fechaStr = grupo.dataset.fecha;
                if (!fechaStr || periodo === 'todo') {
                    grupo.style.display = 'block';
                    return;
                }

                const fecha = new Date(fechaStr + 'T00:00:00');

                let mostrar = false;
                if (periodo === 'hoy') mostrar = fecha >= hoy;
                if (periodo === 'semana') mostrar = fecha >= inicioSemana;
                if (periodo === 'mes') mostrar = fecha >= inicioMes;

                grupo.style.display = mostrar ? 'block' : 'none';
            });
        }
    </script>
</body>

</html>
