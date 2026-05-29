<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Historial de entregas</title>
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
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .btn-back {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #333;
        }

        .btn-back svg {
            width: 22px;
            height: 22px;
        }

        .header-logo img {
            height: 36px;
        }

        /* Stats hero */
        .stats-hero {
            background: linear-gradient(135deg, #a8df11, #7cc10a);
            padding: 1.5rem 1.25rem 2.5rem;
            position: relative;
        }

        .stats-hero::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 24px;
            background: white;
            border-radius: 24px 24px 0 0;
        }

        .stats-title {
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.75);
            margin-bottom: 1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .stat-box {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 0.85rem;
            padding: 0.85rem;
        }

        .stat-val {
            font-size: 1.5rem;
            font-weight: 900;
            color: white;
        }

        .stat-label {
            font-size: 0.68rem;
            color: rgba(255, 255, 255, 0.75);
            margin-top: 0.1rem;
        }

        .body {
            flex: 1;
            padding: 1.25rem 1.25rem 7rem;
        }

        .seccion-titulo {
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #7ab80e;
            margin-bottom: 0.75rem;
        }

        /* Entrega card */
        .entrega-card {
            border: 1.5px solid #e8f5d0;
            border-radius: 1rem;
            overflow: hidden;
            margin-bottom: 0.75rem;
        }

        .entrega-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.85rem 1rem;
            background: #f8fdf0;
        }

        .entrega-tienda {
            font-size: 0.88rem;
            font-weight: 800;
            color: #111;
        }

        .entrega-fecha {
            font-size: 0.68rem;
            color: #aaa;
            margin-top: 0.1rem;
        }

        .entrega-total {
            font-size: 1rem;
            font-weight: 900;
            color: #4a8a06;
        }

        .entrega-body {
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .entrega-cliente {
            font-size: 0.78rem;
            color: #555;
        }

        .entrega-cliente span {
            font-weight: 700;
            color: #111;
        }

        .entrega-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            background: #f0fde0;
            border: 1px solid #c6f135;
            color: #4a8a06;
            font-size: 0.62rem;
            font-weight: 700;
            padding: 0.2rem 0.6rem;
            border-radius: 999px;
        }

        /* Empty */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            padding: 3rem 2rem;
            text-align: center;
        }

        .empty-icon {
            width: 64px;
            height: 64px;
            border-radius: 1.25rem;
            background: #f0fde0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-icon svg {
            width: 32px;
            height: 32px;
            color: #a8df11;
        }

        .empty-state p {
            font-size: 0.88rem;
            font-weight: 700;
            color: #555;
        }

        .empty-state span {
            font-size: 0.75rem;
            color: #aaa;
        }

        /* Nav */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 430px;
            background: white;
            border-top: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-around;
            padding: 0.85rem 0;
            z-index: 10;
        }

        .nav-item {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #bbb;
        }

        .nav-item.active {
            color: #a8df11;
        }

        .nav-item svg {
            width: 24px;
            height: 24px;
        }
    </style>
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
            <div class="header-logo">
                <img src="{{ asset('images/Logo_local_app.png') }}" alt="LocalApp">
            </div>
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

            <p class="seccion-titulo">Historial ({{ $asignaciones->count() }})</p>

            @forelse($asignaciones as $asignacion)
                @php $pedido = $asignacion->pedido; @endphp
                <div class="entrega-card">
                    <div class="entrega-header">
                        <div>
                            <p class="entrega-tienda">{{ $pedido?->tienda?->tie_nombre ?? '—' }}</p>
                            <p class="entrega-fecha">{{ $asignacion->asr_fecha?->format('d/m/Y H:i') }}</p>
                        </div>
                        <p class="entrega-total">${{ number_format($pedido?->ped_total ?? 0, 2) }}</p>
                    </div>
                    <div class="entrega-body">
                        @php $persona = $pedido?->cliente?->user?->persona; @endphp
                        <p class="entrega-cliente">
                            Cliente: <span>{{ $persona?->per_nombre }} {{ $persona?->per_paterno }}</span>
                        </p>
                        <span class="entrega-badge">✓ Entregado</span>
                    </div>
                </div>

            @empty
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
            @endforelse

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
</body>

</html>
