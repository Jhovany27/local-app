<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Recoger pedido</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/repartidor/checklist.css')
</head>

<body>
    <div class="app">

        <div class="header">
            <div class="header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div class="header-text">
                <p>Recoger pedido</p>
                <p>#{{ $pedido->ped_codigo }}</p>
            </div>
        </div>

        <div class="body">

            {{-- Info tienda --}}
            <div class="tienda-info">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18" />
                </svg>
                <div class="tienda-info-text">
                    <p>{{ $pedido->tienda->tie_nombre }}</p>
                    <p>{{ $pedido->tienda->tie_telefono }}</p>
                </div>
            </div>

            {{-- Progreso --}}
            <p class="seccion-titulo">Productos a recoger</p>
            <div class="progreso-wrap">
                <div class="progreso-bar" id="barra" style="width:0%"></div>
            </div>
            <p class="progreso-text" id="progreso-txt">0 / {{ $pedido->detalles->count() }} productos</p>

            {{-- Checklist --}}
            @foreach ($pedido->detalles as $det)
                <div class="prod-item" id="item-{{ $det->det_id }}"
                    onclick="toggleCheck({{ $det->det_id }}, {{ $pedido->detalles->count() }})">
                    <div class="check-box">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </div>
                    @if ($det->producto?->foto_principal)
                        <img src="{{ asset('storage/' . $det->producto->foto_principal) }}" class="prod-img">
                    @else
                        <div class="prod-img-empty">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909" />
                            </svg>
                        </div>
                    @endif
                    <div class="prod-info">
                        <p class="prod-nombre">{{ $det->producto?->pro_nombre }}</p>
                        <p class="prod-qty">Cantidad: {{ $det->det_cantidad }} ·
                            ${{ number_format($det->det_subtotal, 2) }}</p>
                    </div>
                </div>
            @endforeach

        </div>

        <div class="footer-fixed">
            <button type="button" class="btn-listo" id="btn-listo" disabled
                onclick="document.getElementById('modal-recogi').classList.add('open')">
                Ya recogí el pedido
            </button>
            <p class="btn-hint" id="btn-hint-txt">Marca todos los productos para continuar</p>
        </div>
    </div>

    {{-- MODAL CONFIRMACIÓN RECOGIDA --}}
    <div class="modal-overlay" id="modal-recogi">
        <div class="modal">
            <div class="modal-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25" />
                </svg>
            </div>
            <p class="modal-title">¿Ya recogiste todo?</p>
            @php $persona = $pedido->cliente?->user?->persona; @endphp
            <p class="modal-desc">
                @if($esEfectivo && $montoParaTienda !== null)
                    Ahora debes pagar <strong>${{ number_format($montoParaTienda, 2) }}</strong> a la tienda e ingresar su código antes de salir.
                @else
                    Entregarás el pedido a <strong>{{ $persona?->per_nombre }} {{ $persona?->per_paterno }}</strong>.<br>
                    Dirígete a la dirección del cliente para completar la entrega.
                @endif
            </p>
            <div class="modal-btns">
                <button type="button" class="modal-cancel"
                    onclick="document.getElementById('modal-recogi').classList.remove('open')">
                    Revisar
                </button>
                <form method="POST" action="{{ route('repartidor.recogi-pedido', $pedido->ped_id) }}" style="flex:2">
                    @csrf
                    <button type="submit" class="modal-confirm" style="width:100%">
                        @if($esEfectivo)
                            Pagar a la tienda
                        @else
                            Sí, voy a entregar
                        @endif
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let checkedCount = 0;
        const total = {{ $pedido->detalles->count() }};

        function toggleCheck(id, total) {
            const item = document.getElementById('item-' + id);
            const isChecked = item.classList.contains('checked');

            if (isChecked) {
                item.classList.remove('checked');
                checkedCount--;
            } else {
                item.classList.add('checked');
                checkedCount++;
            }

            // Actualizar barra y texto
            const pct = Math.round((checkedCount / total) * 100);
            document.getElementById('barra').style.width = pct + '%';
            document.getElementById('progreso-txt').textContent = checkedCount + ' / ' + total + ' productos';

            // Habilitar botón solo cuando todos estén marcados
            const btn = document.getElementById('btn-listo');
            if (checkedCount === total) {
                btn.disabled = false;
                document.getElementById('btn-hint-txt').textContent = '¡Listo! Puedes continuar';
            } else {
                btn.disabled = true;
                document.getElementById('btn-hint-txt').textContent = 'Marca todos los productos para continuar';
            }
        }
    </script>
</body>

</html>
