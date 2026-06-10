<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis pedidos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/cliente/mis-pedidos.css', 'resources/js/app.js'])
</head>

<body>
    <div class="app">

        <div class="header">
            <a href="{{ route('cliente.index') }}" class="header-back">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </a>
            <div class="header-logo"><img src="{{ asset('images/Logo_local_app.png') }}" alt="LocalApp"></div>
            <div style="width:22px"></div>
        </div>

        <div class="body">

            <h1 class="page-title">Mis pedidos</h1>

            @if (session('success'))
                <div class="success-msg">{{ session('success') }}</div>
            @endif

            @forelse($pedidos as $pedido)
                @php
                    $esDomicilio = strtolower($pedido->ped_tipo_entrega) === 'domicilio';
                    $asignacion = $pedido->asignacion;
                    $asr = $asignacion ? (int) $asignacion->asr_estado : null;

                    if ($pedido->ped_estado === 'cancelado') {
                        $badgeClass = 'badge-cancelado';
                        $badgeLabel = 'Cancelado';
                        $badgePulse = false;
                    } elseif ($pedido->ped_estado === 'completado') {
                        $badgeClass = 'badge-completado';
                        $badgeLabel = 'Completado';
                        $badgePulse = false;
                    } elseif ($pedido->ped_estado === 'pendiente') {
                        $badgeClass = 'badge-pendiente';
                        $badgeLabel = 'Pendiente';
                        $badgePulse = false;
                    } elseif ($pedido->ped_estado === 'en_preparacion') {
                        $badgeClass = 'badge-preparacion';
                        $badgeLabel = 'En preparación';
                        $badgePulse = true;
                    } elseif ($pedido->ped_estado === 'listo') {
                        if ($esDomicilio) {
                            if ($asr === null) {
                                $badgeClass = 'badge-listo-tienda';
                                $badgeLabel = 'Buscando repartidor';
                                $badgePulse = true;
                            } elseif ($asr === 0) {
                                $badgeClass = 'badge-repartidor-yendo';
                                $badgeLabel = 'Repartidor en camino';
                                $badgePulse = true;
                            } elseif ($asr === 1) {
                                $badgeClass = 'badge-repartidor-recoge';
                                $badgeLabel = 'Repartidor recogiendo';
                                $badgePulse = true;
                            } elseif ($asr === 2) {
                                $badgeClass = 'badge-repartidor-camino';
                                $badgeLabel = 'En camino a ti';
                                $badgePulse = true;
                            } else {
                                $badgeClass = 'badge-completado';
                                $badgeLabel = 'Completado';
                                $badgePulse = false;
                            }
                        } else {
                            $badgeClass = 'badge-listo-tienda';
                            $badgeLabel = 'Listo para recoger';
                            $badgePulse = false;
                        }
                    } else {
                        $badgeClass = 'badge-pendiente';
                        $badgeLabel = $pedido->ped_estado;
                        $badgePulse = false;
                    }

                    // Status timeline
                    if ($esDomicilio) {
                        $statuses = [
                            ['label' => 'Pedido recibido', 'sub' => 'La tienda revisará tu pedido', 'done' => true],
                            [
                                'label' => 'En preparación',
                                'sub' => 'La tienda está preparando tu pedido',
                                'done' => in_array($pedido->ped_estado, ['en_preparacion', 'listo', 'completado']),
                            ],
                            [
                                'label' => 'Listo',
                                'sub' => 'Tu pedido está listo',
                                'done' => in_array($pedido->ped_estado, ['listo', 'completado']),
                            ],
                            [
                                'label' => 'Repartidor en camino',
                                'sub' => 'El repartidor va a la tienda',
                                'done' => in_array($pedido->ped_estado, ['listo', 'completado']) && $asr >= 0,
                            ],
                            [
                                'label' => 'Recogiendo pedido',
                                'sub' => 'El repartidor recoge tu pedido',
                                'done' => in_array($pedido->ped_estado, ['listo', 'completado']) && $asr >= 1,
                            ],
                            [
                                'label' => 'En camino a ti',
                                'sub' => 'El repartidor va a tu domicilio',
                                'done' => in_array($pedido->ped_estado, ['listo', 'completado']) && $asr >= 2,
                            ],
                            [
                                'label' => 'Entregado',
                                'sub' => 'Pedido completado',
                                'done' => $pedido->ped_estado === 'completado',
                            ],
                        ];
                    } else {
                        $statuses = [
                            ['label' => 'Pedido recibido', 'sub' => 'La tienda revisará tu pedido', 'done' => true],
                            [
                                'label' => 'En preparación',
                                'sub' => 'La tienda está preparando tu pedido',
                                'done' => in_array($pedido->ped_estado, ['en_preparacion', 'listo', 'completado']),
                            ],
                            [
                                'label' => 'Listo para recoger',
                                'sub' => 'Pasa a recoger tu pedido',
                                'done' => in_array($pedido->ped_estado, ['listo', 'completado']),
                            ],
                            [
                                'label' => 'Completado',
                                'sub' => 'Pedido entregado',
                                'done' => $pedido->ped_estado === 'completado',
                            ],
                        ];
                    }
                @endphp

                <div class="pedido-card" id="pedido-{{ $pedido->ped_id }}"
                    onclick="abrirDetalle({{ $pedido->ped_id }})">

                    <div class="pedido-header">
                        <div>
                            <p class="pedido-tienda">{{ $pedido->tienda->tie_nombre }}</p>
                            <p class="pedido-fecha">{{ $pedido->ped_fecha_pedido->format('d/m/Y H:i') }}</p>
                        </div>
                        <span class="badge {{ $badgeClass }}">
                            @if ($badgePulse)
                                <span class="badge-dot badge-pulse" style="background:currentColor;"></span>
                            @endif
                            {{ $badgeLabel }}
                        </span>
                    </div>

                    {{-- TRACKER domicilio --}}
                    @if ($esDomicilio && $pedido->ped_estado === 'listo' && $asr !== null)
                        @php $steps = [['label'=>'Listo','done'=>true],['label'=>'Rep. en camino','done'=>$asr>=0],['label'=>'Recogiendo','done'=>$asr>=1],['label'=>'En camino a ti','done'=>$asr>=2],['label'=>'Entregado','done'=>$asr>=3]]; @endphp
                        <div class="tracker">
                            <p class="tracker-label">Seguimiento</p>
                            <div class="tracker-steps">
                                @foreach ($steps as $i => $step)
                                    @php
                                        $isDone = $step['done'];
                                        $isActive = $i === $asr + 1;
                                    @endphp
                                    <div class="tracker-step {{ $isDone ? 'done' : ($isActive ? 'active' : '') }}">
                                        <div
                                            class="tracker-circle {{ $isDone ? 'done' : ($isActive ? 'active' : 'pending') }}">
                                            @if ($isDone)
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m4.5 12.75 6 6 9-13.5" />
                                                </svg>
                                            @endif
                                        </div>
                                        <span
                                            class="tracker-txt {{ $isActive ? 'active' : '' }}">{{ $step['label'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="pedido-productos">
                        @foreach ($pedido->detalles->take(3) as $det)
                            <div class="prod-mini">
                                @if ($det->producto?->foto_principal)
                                    <img src="{{ asset('storage/' . $det->producto->foto_principal) }}"
                                        class="prod-mini-img">
                                @else
                                    <div class="prod-mini-img" style="background:#f0fde0;"></div>
                                @endif
                                <span class="prod-mini-nombre">{{ $det->producto?->pro_nombre }}</span>
                                <span class="prod-mini-qty">×{{ $det->det_cantidad }}</span>
                            </div>
                        @endforeach
                        @if ($pedido->detalles->count() > 3)
                            <p style="font-size:0.7rem;color:#aaa;margin-top:0.25rem;">
                                +{{ $pedido->detalles->count() - 3 }} producto(s) más</p>
                        @endif
                    </div>

                    <div class="pedido-footer">
                        <div>
                            <span class="pedido-total">${{ number_format($pedido->ped_total, 2) }}</span>
                            @if ($esDomicilio && $pedido->ped_costo_envio > 0)
                                <p style="font-size:0.65rem;color:#aaa;margin-top:0.1rem;">Incluye
                                    ${{ number_format($pedido->ped_costo_envio, 2) }} de envío</p>
                            @endif
                        </div>
                        <span class="pedido-tipo">{{ $esDomicilio ? 'Envío a domicilio' : 'Recoger en tienda' }}</span>
                    </div>

                </div>

                {{-- MODAL DETALLE --}}
                <div class="modal-detalle" id="modal-{{ $pedido->ped_id }}">
                    <div class="modal-detalle-inner">

                        <div class="modal-detalle-header">
                            <p class="modal-detalle-title">#{{ $pedido->ped_codigo ?? 'Pedido' }}</p>
                            <button class="modal-close"
                                onclick="cerrarDetalle({{ $pedido->ped_id }});event.stopPropagation()">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        {{-- INFO GENERAL --}}
                        <div class="modal-section">
                            <p class="modal-section-label">Información</p>
                            <div class="modal-info-row">
                                <span class="modal-info-key">Tienda</span>
                                <span class="modal-info-val">{{ $pedido->tienda->tie_nombre }}</span>
                            </div>
                            <div class="modal-info-row">
                                <span class="modal-info-key">Fecha</span>
                                <span
                                    class="modal-info-val">{{ $pedido->ped_fecha_pedido->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="modal-info-row">
                                <span class="modal-info-key">Tipo de entrega</span>
                                <span
                                    class="modal-info-val">{{ $esDomicilio ? 'Domicilio' : 'Recoger en tienda' }}</span>
                            </div>
                            <div class="modal-info-row">
                                <span class="modal-info-key">Método de pago</span>
                                <span class="modal-info-val"
                                    style="color:{{ strtolower($pedido->pago?->pag_metodo_pago) === 'tarjeta' ? '#1d4ed8' : '#4a8a06' }}">
                                    {{ strtolower($pedido->pago?->pag_metodo_pago) === 'tarjeta' ? 'Tarjeta' : 'Efectivo' }}
                                </span>
                            </div>
                            <div class="modal-info-row">
                                <span class="modal-info-key">Estado</span>
                                <span class="badge {{ $badgeClass }}"
                                    style="font-size:0.6rem;">{{ $badgeLabel }}</span>
                            </div>
                        </div>

                        {{-- PRODUCTOS --}}
                        <div class="modal-section" style="margin-top:1rem;">
                            <p class="modal-section-label">Productos</p>
                            @foreach ($pedido->detalles as $det)
                                <div class="prod-detalle-row">
                                    @if ($det->producto?->foto_principal)
                                        <img src="{{ asset('storage/' . $det->producto->foto_principal) }}"
                                            class="prod-detalle-img">
                                    @else
                                        <div class="prod-detalle-img" style="background:#f0fde0;border-radius:0.5rem;">
                                        </div>
                                    @endif
                                    <div style="flex:1;">
                                        <p class="prod-detalle-nombre">{{ $det->producto?->pro_nombre }}</p>
                                        <p class="prod-detalle-info">×{{ $det->det_cantidad }} —
                                            ${{ number_format($det->det_precio_unitario, 2) }} c/u</p>
                                    </div>
                                    <span
                                        class="prod-detalle-precio">${{ number_format($det->det_subtotal, 2) }}</span>
                                </div>
                            @endforeach

                            {{-- Totales --}}
                            <div
                                style="margin-top:0.75rem;background:#f8fdf0;border:1px solid #e8f5d0;border-radius:0.75rem;padding:0.75rem;">
                                <div style="display:flex;justify-content:space-between;margin-bottom:0.35rem;">
                                    <span style="font-size:0.78rem;color:#888;">Subtotal</span>
                                    <span
                                        style="font-size:0.78rem;font-weight:700;color:#111;">${{ number_format($pedido->detalles->sum('det_subtotal'), 2) }}</span>
                                </div>
                                @if ($esDomicilio && $pedido->ped_costo_envio > 0)
                                    <div style="display:flex;justify-content:space-between;margin-bottom:0.35rem;">
                                        <span style="font-size:0.78rem;color:#888;">Envío</span>
                                        <span
                                            style="font-size:0.78rem;font-weight:700;color:#1d4ed8;">${{ number_format($pedido->ped_costo_envio, 2) }}</span>
                                    </div>
                                @endif
                                <div
                                    style="display:flex;justify-content:space-between;border-top:1px solid #e8f5d0;padding-top:0.35rem;margin-top:0.1rem;">
                                    <span style="font-size:0.85rem;font-weight:800;color:#111;">Total</span>
                                    <span
                                        style="font-size:1rem;font-weight:900;color:#4a8a06;">${{ number_format($pedido->ped_total, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- STATUS TIMELINE --}}
                        <div class="modal-section" style="margin-top:1rem;">
                            <p class="modal-section-label">Estado del pedido</p>
                            <div class="status-timeline" style="margin-top:0.5rem;">
                                @foreach ($statuses as $i => $status)
                                    @php
                                        $isLast = $i === count($statuses) - 1;
                                        $isActive = !$status['done'] && ($i === 0 || $statuses[$i - 1]['done']);
                                    @endphp
                                    <div class="status-item {{ $status['done'] ? 'done' : '' }}"
                                        style="padding-bottom:{{ $isLast ? '0' : '0.1rem' }};">
                                        @if (!$isLast)
                                            <div class="status-line"></div>
                                        @endif
                                        <div class="status-dot-wrap">
                                            <div
                                                class="status-dot {{ $status['done'] ? 'done' : ($isActive ? 'active' : 'pending') }}">
                                                @if ($status['done'])
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m4.5 12.75 6 6 9-13.5" />
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="status-txt">
                                            <p
                                                class="status-txt-label {{ !$status['done'] && !$isActive ? 'pending' : '' }}">
                                                {{ $status['label'] }}</p>
                                            <p class="status-txt-sub">{{ $status['sub'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

            @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15" />
                        </svg>
                    </div>
                    <p>Sin pedidos aún</p>
                    <span>Haz tu primer pedido desde las tiendas</span>
                    <a href="{{ route('cliente.index') }}" class="btn-ir">Ver tiendas</a>
                </div>
            @endforelse

        </div>

        <nav class="bottom-nav">
            <a href="{{ route('cliente.index') }}" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
            </a>
            <a href="{{ route('carrito.index') }}" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
            </a>
            <a href="{{ route('cliente.pedidos') }}" class="nav-item active">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15" />
                </svg>
            </a>
            @auth
                <a href="{{ route('cliente.perfil') }}" class="nav-item">
                @else
                    <a href="{{ route('cliente.login') }}" class="nav-item">
                    @endauth
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </a>
        </nav>

    </div>

    @auth
        <script>
            // ── MODAL DETALLE ─────────────────────────────────────
            function abrirDetalle(id) {
                document.getElementById('modal-' + id).classList.add('open');
                document.body.style.overflow = 'hidden';
            }

            function cerrarDetalle(id) {
                document.getElementById('modal-' + id).classList.remove('open');
                document.body.style.overflow = '';
            }

            // Cerrar al tocar fondo
            document.querySelectorAll('.modal-detalle').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.classList.remove('open');
                        document.body.style.overflow = '';
                    }
                });
            });

            // ── REVERB ────────────────────────────────────────────
            function iniciarEcho() {
                if (!window.Echo) {
                    setTimeout(iniciarEcho, 200);
                    return;
                }

                const userId = {{ Auth::id() }};

                window.Echo.channel(`pedido.${userId}`)
                    .listen('.pedido.actualizado', (data) => {
                        const card = document.getElementById(`pedido-${data.pedidoId}`);
                        if (!card) return;

                        const badge = card.querySelector('.badge');
                        if (badge) {
                            badge.className = `badge ${data.badgeClass}`;
                            badge.innerHTML = data.badgePulse ?
                                `<span class="badge-dot badge-pulse" style="background:currentColor;"></span> ${data.badgeLabel}` :
                                data.badgeLabel;
                        }

                        const tracker = card.querySelector('.tracker-steps');
                        if (tracker && data.asrEstado !== null) {
                            actualizarTracker(tracker, data.asrEstado);
                        }

                        mostrarToast(`Tu pedido: ${data.badgeLabel}`);
                    });
            }

            iniciarEcho();

            function actualizarTracker(tracker, asr) {
                const steps = tracker.querySelectorAll('.tracker-step');
                steps.forEach((step, i) => {
                    const circle = step.querySelector('.tracker-circle');
                    const txt = step.querySelector('.tracker-txt');
                    const done = i <= asr;
                    const active = i === asr + 1;
                    step.className = `tracker-step ${done ? 'done' : active ? 'active' : ''}`;
                    circle.className = `tracker-circle ${done ? 'done' : active ? 'active' : 'pending'}`;
                    txt.className = `tracker-txt ${active ? 'active' : ''}`;
                });
            }

            function mostrarToast(msg) {
                const toast = document.createElement('div');
                toast.style.cssText = `
            position:fixed;top:1rem;left:50%;transform:translateX(-50%);
            background:#a8df11;color:#111;font-weight:700;font-size:0.82rem;
            padding:0.65rem 1.25rem;border-radius:999px;z-index:9999;
            box-shadow:0 4px 16px rgba(168,223,17,0.4);transition:opacity 0.3s;
        `;
                toast.textContent = msg;
                document.body.appendChild(toast);
                setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }
        </script>
    @endauth

</body>

</html>
