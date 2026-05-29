<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Recoger pedido</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #f0f2f0;
            display: flex;
            justify-content: center;
            font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif;
            min-height: 100vh;
        }

        .app {
            width: 100%;
            max-width: 430px;
            min-height: 100vh;
            background: white;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: #edf3e3;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .header-icon {
            width: 36px;
            height: 36px;
            background: #a8df11;
            border-radius: 0.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header-icon svg {
            width: 18px;
            height: 18px;
            color: white;
        }

        .header-text p:first-child {
            font-size: 0.88rem;
            font-weight: 800;
            color: #111;
        }

        .header-text p:last-child {
            font-size: 0.7rem;
            color: #888;
        }

        .body {
            flex: 1;
            padding: 1rem 1.25rem 8rem;
        }

        .tienda-info {
            background: #f8fdf0;
            border: 1.5px solid #e8f5d0;
            border-radius: 0.85rem;
            padding: 0.85rem 1rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .tienda-info svg {
            width: 18px;
            height: 18px;
            color: #a8df11;
            flex-shrink: 0;
        }

        .tienda-info-text p:first-child {
            font-size: 0.82rem;
            font-weight: 700;
            color: #111;
        }

        .tienda-info-text p:last-child {
            font-size: 0.7rem;
            color: #888;
            margin-top: 0.1rem;
        }

        .seccion-titulo {
            font-size: 0.62rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #7ab80e;
            margin-bottom: 0.75rem;
        }

        .progreso-wrap {
            background: #f0f0f0;
            border-radius: 999px;
            height: 6px;
            margin-bottom: 1.25rem;
            overflow: hidden;
        }

        .progreso-bar {
            height: 100%;
            background: linear-gradient(90deg, #a8df11, #7cc10a);
            border-radius: 999px;
            transition: width 0.3s;
        }

        .progreso-text {
            font-size: 0.72rem;
            color: #888;
            text-align: right;
            margin-top: 0.3rem;
            margin-bottom: 1rem;
        }

        /* Checklist */
        .prod-item {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.9rem 1rem;
            border: 1.5px solid #e8f5d0;
            border-radius: 0.85rem;
            margin-bottom: 0.6rem;
            cursor: pointer;
            transition: all 0.2s;
            user-select: none;
        }

        .prod-item.checked {
            background: #f8fdf0;
            border-color: #a8df11;
        }

        .prod-item.checked .prod-nombre {
            text-decoration: line-through;
            color: #aaa;
        }

        .check-box {
            width: 24px;
            height: 24px;
            border-radius: 0.5rem;
            border: 2px solid #d0d0d0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s;
        }

        .prod-item.checked .check-box {
            background: #a8df11;
            border-color: #a8df11;
        }

        .check-box svg {
            width: 14px;
            height: 14px;
            color: white;
            display: none;
        }

        .prod-item.checked .check-box svg {
            display: block;
        }

        .prod-img {
            width: 44px;
            height: 44px;
            object-fit: contain;
            border-radius: 0.5rem;
            background: #f8f8f8;
            flex-shrink: 0;
        }

        .prod-img-empty {
            width: 44px;
            height: 44px;
            border-radius: 0.5rem;
            background: #f0fde0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .prod-img-empty svg {
            width: 20px;
            height: 20px;
            color: #c6f135;
        }

        .prod-info {
            flex: 1;
        }

        .prod-nombre {
            font-size: 0.85rem;
            font-weight: 700;
            color: #111;
            transition: color 0.2s;
        }

        .prod-qty {
            font-size: 0.72rem;
            color: #888;
            margin-top: 0.1rem;
        }

        /* Footer */
        .footer-fixed {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 430px;
            background: white;
            border-top: 1px solid #f0f0f0;
            padding: 1rem 1.25rem;
            z-index: 10;
        }

        .btn-listo {
            width: 100%;
            background: linear-gradient(135deg, #a8df11, #7cc10a);
            color: #1a1a1a;
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: 800;
            padding: 0.9rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(168, 223, 17, 0.35);
            transition: opacity 0.2s;
        }

        .btn-listo:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            box-shadow: none;
        }

        .btn-hint {
            font-size: 0.68rem;
            color: #aaa;
            text-align: center;
            margin-top: 0.5rem;
        }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            align-items: flex-end;
            justify-content: center;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal {
            background: white;
            border-radius: 1.5rem 1.5rem 0 0;
            padding: 2rem 1.5rem;
            width: 100%;
            max-width: 430px;
        }

        .modal-icon {
            width: 56px;
            height: 56px;
            background: #f0fde0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .modal-icon svg {
            width: 28px;
            height: 28px;
            color: #4a8a06;
        }

        .modal-title {
            font-size: 1rem;
            font-weight: 900;
            color: #111;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .modal-desc {
            font-size: 0.82rem;
            color: #888;
            text-align: center;
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }

        .modal-btns {
            display: flex;
            gap: 0.75rem;
        }

        .modal-cancel {
            flex: 1;
            background: #f0f0f0;
            color: #555;
            font-family: inherit;
            font-size: 0.88rem;
            font-weight: 700;
            padding: 0.85rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
        }

        .modal-confirm {
            flex: 2;
            background: linear-gradient(135deg, #a8df11, #7cc10a);
            color: #1a1a1a;
            font-family: inherit;
            font-size: 0.88rem;
            font-weight: 800;
            padding: 0.85rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
        }
    </style>
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
                Entregarás el pedido a <strong>{{ $persona?->per_nombre }} {{ $persona?->per_paterno }}</strong>.<br>
                Dirígete a la dirección del cliente para completar la entrega.
            </p>
            <div class="modal-btns">
                <button type="button" class="modal-cancel"
                    onclick="document.getElementById('modal-recogi').classList.remove('open')">
                    Revisar
                </button>
                <form method="POST" action="{{ route('repartidor.recogi-pedido', $pedido->ped_id) }}" style="flex:2">
                    @csrf
                    <button type="submit" class="modal-confirm" style="width:100%">
                        Sí, voy a entregar
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
