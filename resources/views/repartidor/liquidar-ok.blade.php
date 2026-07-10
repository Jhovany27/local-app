<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Liquidado</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/repartidor/perfil.css')
    <style>
        .lok-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
            gap: 1.5rem;
            text-align: center;
        }

        .lok-circle {
            width: 90px; height: 90px;
            border-radius: 50%;
            background: linear-gradient(135deg, #a8df11, #7cc10a);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 0 0 rgba(168,223,17,.5);
            animation: pulse-green 1s ease-out 0.3s forwards;
        }
        @keyframes pulse-green {
            0%   { box-shadow: 0 0 0 0 rgba(168,223,17,.5); transform: scale(0.8); opacity: 0; }
            50%  { box-shadow: 0 0 0 20px rgba(168,223,17,.15); transform: scale(1.08); opacity: 1; }
            100% { box-shadow: 0 0 0 0 rgba(168,223,17,0); transform: scale(1); opacity: 1; }
        }
        .lok-circle svg { width: 44px; height: 44px; color: white; }

        .lok-titulo { font-size: 1.2rem; font-weight: 900; color: #1a1a1a; }
        .lok-sub     { font-size: .85rem; color: #888; line-height: 1.5; max-width: 260px; }

        .lok-resumen {
            background: #f8fdf0; border: 1.5px solid #d4edaa; border-radius: 14px;
            padding: 1rem 1.25rem; width: 100%;
            display: flex; flex-direction: column; gap: .5rem;
        }
        .lok-res-row { display: flex; justify-content: space-between; font-size: .83rem; }
        .lok-res-key { color: #888; }
        .lok-res-val { font-weight: 700; color: #1a1a1a; }
        .lok-res-total { font-size: .95rem; font-weight: 900; color: #4a8a06; }
        .lok-divider { height: 1px; background: #e8f5d0; }

        .lok-btn {
            width: 100%; padding: .85rem;
            background: linear-gradient(135deg,#a8df11,#7cc10a);
            border: none; border-radius: 12px;
            font-family: 'Instrument Sans', sans-serif;
            font-size: 1rem; font-weight: 800; color: #1a1a1a;
            cursor: pointer; text-decoration: none;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 16px rgba(168,223,17,.3);
        }
    </style>
</head>
<body>
<div class="app">

    <div class="header">
        <div style="width:22px"></div>
        <div class="header-logo"><img src="{{ asset('images/Logo_local_app.png') }}" alt="LocalApp"></div>
        <div style="width:22px"></div>
    </div>

    <div class="lok-body">

        <div class="lok-circle">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
            </svg>
        </div>

        <div>
            <p class="lok-titulo">¡Pedido liquidado!</p>
            <p class="lok-sub" style="margin-top:.4rem;">La tienda confirmó la recepción del efectivo. Todo en orden.</p>
        </div>

        <div class="lok-resumen">
            <div class="lok-res-row">
                <span class="lok-res-key">Pedido</span>
                <span class="lok-res-val">#{{ $pedido->ped_codigo }}</span>
            </div>
            <div class="lok-divider"></div>
            <div class="lok-res-row">
                <span class="lok-res-key">Subtotal productos</span>
                <span class="lok-res-val">${{ number_format($pedido->ped_total - $pedido->ped_costo_envio, 2) }}</span>
            </div>
            <div class="lok-res-row" style="color:#ef4444;">
                <span class="lok-res-key" style="color:#ef4444;">Comisión plataforma ({{ $pctComision }}%)</span>
                <span class="lok-res-val" style="color:#ef4444;">−${{ number_format($comision, 2) }}</span>
            </div>
            <div class="lok-divider"></div>
            <div class="lok-res-row">
                <span class="lok-res-key">Entregaste a la tienda</span>
                <span class="lok-res-total">${{ number_format($montoParaTienda, 2) }}</span>
            </div>
            <div class="lok-res-row">
                <span class="lok-res-key">Tu ganancia (envío)</span>
                <span class="lok-res-val">${{ number_format($pedido->ped_costo_envio, 2) }}</span>
            </div>
            <div class="lok-res-row">
                <span class="lok-res-key">Comisión que retuviste</span>
                <span class="lok-res-val" style="color:#b45309;">${{ number_format($comision, 2) }} (registrada como deuda)</span>
            </div>
            <div class="lok-divider"></div>
            <div class="lok-res-row">
                <span class="lok-res-key">Liquidado el</span>
                <span class="lok-res-val">{{ $pedido->ped_liquidado_at?->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        <a href="{{ route('repartidor.index') }}" class="lok-btn">Volver al inicio</a>

    </div>

</div>
</body>
</html>
