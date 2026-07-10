<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Realizar pago</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/cliente/checkout.css')
    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>
    <div class="app">

        <div class="header">
            <a href="{{ route('carrito.index') }}" class="header-back">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </a>
            <h1>Realizar pago</h1>
            <div style="width:22px"></div>
        </div>

        <div class="body">

            {{-- RESUMEN --}}
            <p class="seccion-titulo">Resumen del pedido</p>
            <div class="resumen-card">
                @foreach ($pedido->detalles as $det)
                    <div class="resumen-row">
                        <span class="resumen-nombre">{{ $det->producto?->pro_nombre }} × {{ $det->det_cantidad }}</span>
                        <span class="resumen-precio">${{ number_format($det->det_subtotal, 2) }}</span>
                    </div>
                @endforeach
                <div class="resumen-total-row">
                    <span class="resumen-total-label">Importe</span>
                    <span class="resumen-total-val">${{ number_format($pedido->ped_total, 2) }}</span>
                </div>
                {{-- DESGLOSE CON ENVÍO --}}
                <div class="resumen-total-row"
                    style="border-top:1px solid #f0f9e0;padding-top:0.5rem;margin-top:0.25rem;">
                    <span style="font-size:0.82rem;color:#888;">Subtotal productos</span>
                    <span style="font-size:0.82rem;font-weight:700;color:#111;">
                        ${{ number_format($pedido->detalles->sum('det_subtotal'), 2) }}
                    </span>
                </div>

                @if ($envio && $envio['costo_envio'] > 0)
                    <div class="resumen-total-row" style="padding-top:0.25rem;" id="fila-envio">
                        <span style="font-size:0.82rem;color:#888;">
                            Envío ({{ $envio['distancia_km'] }} km)
                        </span>
                        <span style="font-size:0.82rem;font-weight:700;color:#1d4ed8;" id="txt-costo-envio">
                            ${{ number_format($envio['costo_envio'], 2) }}
                        </span>
                    </div>
                @endif

                <div class="resumen-total-row" style="padding-top:0.5rem;">
                    <span class="resumen-total-label">Total</span>
                    <span class="resumen-total-val" id="txt-total">
                        ${{ number_format($pedido->detalles->sum('det_subtotal') + ($envio['costo_envio'] ?? 0), 2) }}
                    </span>
                </div>

            </div>

            {{-- DIRECCIÓN --}}
            <p class="seccion-titulo">Dirección de entrega</p>
            @if ($direccion)
                <div style="display:flex;gap:0.65rem;align-items:stretch;margin-bottom:1.25rem;">
                    {{-- Card dirección --}}
                    <a href="{{ route('cliente.direcciones', ['redirect' => url()->current()]) }}" class="dir-card"
                        style="flex:1;margin-bottom:0;">
                        <div class="dir-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                        </div>
                        <div class="dir-texto">
                            <p>{{ $direccion->drc_calle }}, {{ $direccion->drc_ciudad }}</p>
                            <p>{{ $direccion->drc_estado }} — Toca para cambiar</p>
                        </div>
                    </a>

                    {{--  Botón ver mapa --}}
                    <button type="button" onclick="abrirMapa()"
                        style="background:#f0fde0;border:1.5px solid #e8f5d0;border-radius:1rem;padding:0 0.85rem;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:border-color 0.2s;"
                        onmouseover="this.style.borderColor='#a8df11'" onmouseout="this.style.borderColor='#e8f5d0'">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="#4a8a06" style="width:20px;height:20px;">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                        </svg>
                    </button>
                </div>
            @else
                <a href="{{ route('cliente.direcciones') }}" class="dir-card">
                    <div class="dir-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <div class="dir-texto">
                        <p>Agregar dirección de entrega</p>
                        <p>Requerida para envío a domicilio</p>
                    </div>
                </a>
            @endif

            {{-- TIPO ENTREGA --}}
            <p class="seccion-titulo">Tipo de entrega</p>
            <div class="entrega-opts">
                <label class="entrega-opt">
                    <input type="radio" name="tipo_entrega" value="domicilio" checked>
                    <div class="entrega-opt-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25" />
                        </svg>
                    </div>
                    <div class="entrega-opt-text">
                        <p>Envío a domicilio</p>
                        <p>Te lo llevamos a tu dirección</p>
                    </div>
                </label>
                <label class="entrega-opt">
                    <input type="radio" name="tipo_entrega" value="recoger">
                    <div class="entrega-opt-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18" />
                        </svg>
                    </div>
                    <div class="entrega-opt-text">
                        <p>Recoger en tienda</p>
                        <p>Pasa por tu pedido directamente</p>
                    </div>
                </label>
            </div>

            {{-- MÉTODO DE PAGO --}}
            <p class="seccion-titulo">Método de pago</p>
            <div class="pago-opts">
                {{-- EFECTIVO --}}
                <label class="pago-opt">
                    <input type="radio" name="metodo_pago" value="efectivo" checked
                        onchange="toggleStripe(false)">
                    <div class="pago-opt-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                        </svg>
                    </div>
                    <div class="pago-opt-text">
                        <p>Pago en efectivo</p>
                        <p>Pagas al recibir tu pedido</p>
                    </div>
                </label>

                {{-- TARJETA --}}
                <label class="pago-opt">
                    <input type="radio" name="metodo_pago" value="tarjeta" onchange="toggleStripe(true)">
                    <div class="pago-opt-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                        </svg>
                    </div>
                    <div class="pago-opt-text">
                        <p>Pago con tarjeta</p>
                        <p>Crédito o débito — procesado por Stripe</p>
                    </div>
                </label>
            </div>

            {{-- FORMULARIO STRIPE --}}
            <div class="stripe-form" id="stripe-form">

                @if($tarjetas->isNotEmpty())
                    {{-- TARJETAS GUARDADAS --}}
                    <p style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;color:#888;margin-bottom:0.65rem;">
                        Elige una tarjeta
                    </p>

                    <div style="display:flex;flex-direction:column;gap:0.5rem;margin-bottom:0.75rem;">
                        @foreach($tarjetas as $tarjeta)
                            <label class="saved-card-opt" id="lbl-saved-{{ $tarjeta->tar_id }}">
                                <input type="radio" name="tarjeta_seleccion" value="{{ $tarjeta->tar_stripe_pm_id }}"
                                    {{ $loop->first ? 'checked' : '' }}
                                    onchange="seleccionarTarjeta('guardada')">
                                <div class="saved-card-inner">
                                    <div class="saved-card-icon">
                                        @if(strtolower($tarjeta->tar_brand) === 'visa')
                                            <span style="font-size:0.7rem;font-weight:900;color:#1a1f71;letter-spacing:-0.02em;">VISA</span>
                                        @elseif(strtolower($tarjeta->tar_brand) === 'mastercard')
                                            <svg viewBox="0 0 28 20" width="24" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="10" cy="10" r="8" fill="#eb001b"/>
                                                <circle cx="18" cy="10" r="8" fill="#f79e1b"/>
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#888" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" /></svg>
                                        @endif
                                    </div>
                                    <div style="flex:1;">
                                        <p style="font-size:0.8rem;font-weight:700;color:#111;font-family:monospace;">
                                            •••• {{ $tarjeta->tar_last4 }}
                                        </p>
                                        <p style="font-size:0.7rem;color:#999;">
                                            Vence {{ str_pad($tarjeta->tar_exp_month, 2, '0', STR_PAD_LEFT) }}/{{ $tarjeta->tar_exp_year }}
                                        </p>
                                    </div>
                                    @if($tarjeta->tar_es_default)
                                        <span style="background:#a8df11;color:#2d6004;font-size:0.6rem;font-weight:800;padding:0.15rem 0.45rem;border-radius:999px;text-transform:uppercase;letter-spacing:0.05em;">Principal</span>
                                    @endif
                                </div>
                            </label>
                        @endforeach

                        {{-- OPCIÓN: NUEVA TARJETA --}}
                        <label class="saved-card-opt" id="lbl-nueva">
                            <input type="radio" name="tarjeta_seleccion" value="nueva"
                                onchange="seleccionarTarjeta('nueva')">
                            <div class="saved-card-inner">
                                <div class="saved-card-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="#888" style="width:16px;height:16px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </div>
                                <div>
                                    <p style="font-size:0.8rem;font-weight:700;color:#111;">Nueva tarjeta</p>
                                    <p style="font-size:0.7rem;color:#999;">Ingresar datos manualmente</p>
                                </div>
                            </div>
                        </label>
                    </div>

                    {{-- FORMULARIO NUEVA TARJETA (oculto si hay guardadas) --}}
                    <div id="new-card-form" style="display:none;">
                        <div id="card-element"></div>
                        <div id="card-errors" style="color:#dc2626;font-size:0.78rem;margin-top:0.4rem;min-height:1rem;"></div>
                        <label class="guardar-check-label" style="margin-top:0.65rem;">
                            <input type="checkbox" id="chk-guardar" value="1">
                            <span>Guardar esta tarjeta para futuros pagos</span>
                        </label>
                    </div>

                @else
                    {{-- SIN TARJETAS GUARDADAS: formulario directo --}}
                    <p style="font-size:0.75rem;font-weight:700;color:#333;margin-bottom:0.65rem;">Datos de tu tarjeta</p>
                    <div id="card-element"></div>
                    <div id="card-errors" style="color:#dc2626;font-size:0.78rem;margin-top:0.4rem;min-height:1rem;"></div>
                    <label class="guardar-check-label" style="margin-top:0.65rem;">
                        <input type="checkbox" id="chk-guardar" value="1">
                        <span>Guardar esta tarjeta para futuros pagos</span>
                    </label>
                @endif

            </div>

        </div>

        {{-- FOOTER --}}
        <div class="footer-fixed">
            <div class="footer-total">
                <span class="footer-total-label">Importe total</span>
                <span class="footer-total-val" id="footer-total">
                    ${{ number_format($pedido->detalles->sum('det_subtotal') + ($envio['costo_envio'] ?? 0), 2) }}
                </span>
            </div>
            <button type="button" class="btn-pagar" id="btn-pagar" onclick="procesarPago()">
                Pagar
            </button>
        </div>

    </div>

    {{-- OVERLAY ANIMACIÓN --}}
    <div class="pago-overlay" id="overlay-pago">
        <div class="e-circulo">
            <svg class="e-spinner" viewBox="0 0 90 90" fill="none">
                <circle cx="45" cy="45" r="38" stroke="#e8f5d0" stroke-width="6" />
                <path d="M45 7 A38 38 0 0 1 83 45" stroke="#a8df11" stroke-width="6" stroke-linecap="round" />
            </svg>
            <svg class="e-check" id="e-check" viewBox="0 0 90 90" fill="none">
                <circle cx="45" cy="45" r="38" stroke="#a8df11" stroke-width="6" />
                <path class="e-check-path" id="e-check-path" d="M28 46 L40 58 L63 34" stroke="#a8df11"
                    stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
        <div class="e-barra-wrap">
            <div class="e-barra" id="e-barra"></div>
        </div>
        <p class="e-txt" id="e-txt">Procesando tu pago...</p>
    </div>

    {{-- FORM OCULTO PARA EFECTIVO --}}
    <form method="POST" action="{{ route('carrito.confirmar', $pedido->ped_id) }}" id="form-efectivo"
        style="display:none">
        @csrf
        <input type="hidden" name="tipo_entrega" id="tipo-efectivo">
    </form>

    {{-- FORM OCULTO PARA TARJETA --}}
    <form method="POST" action="{{ route('stripe.confirmar') }}" id="form-tarjeta" style="display:none">
        @csrf
        <input type="hidden" name="pedido_id" value="{{ $pedido->ped_id }}">
        <input type="hidden" name="payment_intent_id" id="payment-intent-id">
        <input type="hidden" name="tipo_entrega" id="tipo-tarjeta">
        <input type="hidden" name="payment_method_id" id="pm-id-guardar">
        <input type="hidden" name="guardar_tarjeta" id="guardar-tarjeta-val" value="0">
    </form>


    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        const stripePublicKey = '{{ config('services.stripe.key') }}';
        const stripe = Stripe(stripePublicKey);
        const elements = stripe.elements();

        const cardStyle = {
            base: {
                fontFamily: '"Instrument Sans", sans-serif',
                fontSize: '16px',
                color: '#111',
                '::placeholder': { color: '#aaa' },
            },
            invalid: { color: '#d41b11' },
        };

        const cardElement = elements.create('card', { style: cardStyle });
        cardElement.mount('#card-element');
        cardElement.on('change', function(event) {
            const errors = document.getElementById('card-errors');
            if (errors) errors.textContent = event.error ? event.error.message : '';
        });

        // ── Estado: tarjetas guardadas ──────────────────────
        const hayTarjetasGuardadas = {{ $tarjetas->isNotEmpty() ? 'true' : 'false' }};
        let modoTarjeta = hayTarjetasGuardadas ? 'guardada' : 'nueva'; // 'guardada' | 'nueva'

        function seleccionarTarjeta(modo) {
            modoTarjeta = modo;
            const newCardForm = document.getElementById('new-card-form');
            if (newCardForm) {
                newCardForm.style.display = modo === 'nueva' ? 'block' : 'none';
            }
        }

        function toggleStripe(mostrar) {
            const form = document.getElementById('stripe-form');
            mostrar ? form.classList.add('visible') : form.classList.remove('visible');
        }

        function getTipoEntrega() {
            return document.querySelector('input[name="tipo_entrega"]:checked')?.value ?? 'domicilio';
        }

        function getMetodoPago() {
            return document.querySelector('input[name="metodo_pago"]:checked')?.value ?? 'efectivo';
        }

        function getTarjetaSeleccionada() {
            return document.querySelector('input[name="tarjeta_seleccion"]:checked')?.value ?? null;
        }

        function quiereGuardar() {
            return document.getElementById('chk-guardar')?.checked ?? false;
        }

        async function procesarPago() {
            const metodo = getMetodoPago();
            const tipo   = getTipoEntrega();
            const btn    = document.getElementById('btn-pagar');
            btn.disabled = true;

            if (metodo === 'efectivo') {
                mostrarOverlay();
                document.getElementById('tipo-efectivo').value = tipo;
                setTimeout(() => {
                    completarOverlay('Pedido realizado');
                    setTimeout(() => document.getElementById('form-efectivo').submit(), 1400);
                }, 1200);
                return;
            }

            // ── TARJETA ───────────────────────────────────────
            mostrarOverlay();

            try {
                const tarjetaPmId     = getTarjetaSeleccionada();
                const usandoGuardada  = hayTarjetasGuardadas && modoTarjeta === 'guardada' && tarjetaPmId && tarjetaPmId !== 'nueva';
                const guardar         = !usandoGuardada && quiereGuardar();

                // 1. Crear PaymentIntent
                const res = await fetch('{{ route('stripe.intent') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        pedido_id:      {{ $pedido->ped_id }},
                        tipo_entrega:   tipo,
                        guardar_tarjeta: guardar ? 1 : 0,
                    }),
                });

                const data = await res.json();
                if (!data.client_secret) throw new Error('Sin client_secret');

                // 2. Confirmar con Stripe.js
                let confirmParams;
                if (usandoGuardada) {
                    confirmParams = { payment_method: tarjetaPmId };
                } else {
                    confirmParams = { payment_method: { card: cardElement } };
                }

                const result = await stripe.confirmCardPayment(data.client_secret, confirmParams);

                if (result.error) {
                    ocultarOverlay();
                    const errEl = document.getElementById('card-errors');
                    if (errEl) errEl.textContent = result.error.message;
                    btn.disabled = false;
                    return;
                }

                // 3. Pago exitoso
                completarOverlay('Pago realizado');
                document.getElementById('payment-intent-id').value = result.paymentIntent.id;
                document.getElementById('tipo-tarjeta').value       = tipo;

                if (guardar && result.paymentIntent.payment_method) {
                    document.getElementById('pm-id-guardar').value     = result.paymentIntent.payment_method;
                    document.getElementById('guardar-tarjeta-val').value = '1';
                }

                setTimeout(() => document.getElementById('form-tarjeta').submit(), 1400);

            } catch (e) {
                ocultarOverlay();
                const errEl = document.getElementById('card-errors');
                if (errEl) errEl.textContent = 'Error al procesar el pago.';
                btn.disabled = false;
            }
        }

        function mostrarOverlay() {
            const overlay = document.getElementById('overlay-pago');
            overlay.classList.add('visible');
            let pct = 0;
            window._barraInterval = setInterval(() => {
                pct = Math.min(pct + Math.random() * 15, 85);
                document.getElementById('e-barra').style.width = pct + '%';
            }, 300);
        }

        function completarOverlay(txt) {
            clearInterval(window._barraInterval);
            document.getElementById('e-barra').style.width = '100%';
            setTimeout(() => {
                document.querySelector('.e-spinner').style.display = 'none';
                const check = document.getElementById('e-check');
                check.style.opacity = '1';
                document.getElementById('e-check-path').style.strokeDashoffset = '0';
                document.getElementById('e-txt').textContent = txt;
            }, 350);
        }

        function ocultarOverlay() {
            clearInterval(window._barraInterval);
            document.getElementById('overlay-pago').classList.remove('visible');
            document.getElementById('e-barra').style.width = '0%';
            document.querySelector('.e-spinner').style.display = 'block';
            document.getElementById('e-check').style.opacity = '0';
            document.getElementById('e-check-path').style.strokeDashoffset = '40';
        }

        document.querySelectorAll('input[name="tipo_entrega"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const filaEnvio = document.getElementById('fila-envio');
                const txtTotal = document.getElementById('txt-total');
                const footerTotal = document.getElementById('footer-total'); // 
                const subtotal = {{ $pedido->detalles->sum('det_subtotal') }};
                const costoEnvio = {{ $envio['costo_envio'] ?? 0 }};

                if (this.value === 'domicilio') {
                    if (filaEnvio) filaEnvio.style.display = 'flex';
                    txtTotal.textContent = '$' + (subtotal + costoEnvio).toFixed(2);
                    footerTotal.textContent = '$' + (subtotal + costoEnvio).toFixed(2); // 
                } else {
                    if (filaEnvio) filaEnvio.style.display = 'none';
                    txtTotal.textContent = '$' + subtotal.toFixed(2);
                    footerTotal.textContent = '$' + subtotal.toFixed(2); // 
                }
            });
        });


        //------------------ MAPA RUTA ENTRE TIENDA Y CLIENTE ------------------
        const latTienda = {{ floatval($pedido->tienda->tie_latitud) ?: 17.9869 }};
        const lngTienda = {{ floatval($pedido->tienda->tie_longitud) ?: -92.9303 }};
        const latCliente = {{ floatval($direccion?->drc_latitud) ?: 0 }};
        const lngCliente = {{ floatval($direccion?->drc_longitud) ?: 0 }};
        let mapaIniciado = false;
        let mapaObj;

        function abrirMapa() {
            document.getElementById('modal-mapa').style.display = 'flex';
            if (!mapaIniciado) {
                setTimeout(() => {
                    mapaObj = L.map('mapa-checkout', {
                            attributionControl: false
                        })
                        .setView([latTienda, lngTienda], 14);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19
                    }).addTo(mapaObj);

                    // Pin verde — tienda
                    L.circleMarker([latTienda, lngTienda], {
                        radius: 10,
                        fillColor: '#a8df11',
                        color: 'white',
                        weight: 3,
                        fillOpacity: 1
                    }).addTo(mapaObj).bindPopup('{{ $pedido->tienda->tie_nombre }}');

                    // Pin azul — cliente
                    if (latCliente && lngCliente) {
                        L.circleMarker([latCliente, lngCliente], {
                            radius: 10,
                            fillColor: '#3b82f6',
                            color: 'white',
                            weight: 3,
                            fillOpacity: 1
                        }).addTo(mapaObj).bindPopup('Tu dirección');

                        // Línea entre los dos puntos
                        L.polyline([
                            [latTienda, lngTienda],
                            [latCliente, lngCliente]
                        ], {
                            color: '#a8df11',
                            weight: 3,
                            dashArray: '8, 6',
                            opacity: 0.8
                        }).addTo(mapaObj);

                        mapaObj.fitBounds(
                            L.latLngBounds([latTienda, lngTienda], [latCliente, lngCliente]), {
                                padding: [40, 40]
                            }
                        );
                    }

                    mapaObj.invalidateSize();
                    mapaIniciado = true;
                }, 100);
            } else {
                setTimeout(() => mapaObj.invalidateSize(), 100);
            }
        }

        function cerrarMapa() {
            document.getElementById('modal-mapa').style.display = 'none';
        }

        // ✅ Después — espera a que el DOM cargue
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('modal-mapa').addEventListener('click', function(e) {
                if (e.target === this) cerrarMapa();
            });
        });

    </script>

    {{-- MODAL MAPA --}}
    <div id="modal-mapa"
        style="display:none;position:fixed;inset:0;z-index:9998;background:rgba(0,0,0,0.5);align-items:flex-end;justify-content:center;">
        <div style="background:white;border-radius:1.5rem 1.5rem 0 0;width:100%;max-width:430px;overflow:hidden;">

            {{-- Header modal --}}
            <div
                style="display:flex;align-items:center;justify-content:space-between;padding:1rem 1.25rem;border-bottom:1px solid #f0f0f0;">
                <p style="font-size:0.88rem;font-weight:800;color:#111;">Tu ruta de entrega</p>
                <button type="button" onclick="cerrarMapa()"
                    style="background:none;border:none;cursor:pointer;color:#888;display:flex;align-items:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" style="width:20px;height:20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Mapa --}}
            <div id="mapa-checkout" style="width:100%;height:320px;z-index:1;"></div>

            {{-- Info distancia --}}
            @if ($envio)
                <div style="display:flex;gap:0.75rem;padding:0.85rem 1.25rem;border-top:1px solid #f0f0f0;">
                    <div
                        style="flex:1;background:#f8fdf0;border:1px solid #e8f5d0;border-radius:0.75rem;padding:0.65rem;text-align:center;">
                        <p
                            style="font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:#7ab80e;margin-bottom:0.15rem;">
                            Distancia</p>
                        <p style="font-size:0.92rem;font-weight:900;color:#111;">{{ $envio['distancia_km'] }} km</p>
                    </div>
                    <div
                        style="flex:1;background:#f8fdf0;border:1px solid #e8f5d0;border-radius:0.75rem;padding:0.65rem;text-align:center;">
                        <p
                            style="font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:#7ab80e;margin-bottom:0.15rem;">
                            Costo envío</p>
                        <p style="font-size:0.92rem;font-weight:900;color:#111;">
                            ${{ number_format($envio['costo_envio'], 2) }}</p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</body>

</html>
