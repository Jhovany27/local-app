<x-filament-widgets::widget>
    <div class="tp-wrap">

        <div class="tp-header">
            <div class="tp-header-left">
                <h2 class="tp-title">Tiendas pendientes</h2>
                <span class="tp-count">{{ $this->tiendas->count() }} solicitud(es)</span>
            </div>
        </div>

        @if($this->tiendas->isEmpty())
        <div class="tp-empty">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <p>No hay tiendas pendientes de revisión</p>
        </div>
        @else
        <div class="tp-grid">
            @foreach($this->tiendas as $tienda)
            <div class="tp-card">

                {{-- Fachada --}}
                <div class="tp-fachada">
                    @if($tienda->fachada?->fac_ruta)
                    <img src="{{ asset('storage/'.$tienda->fachada->fac_ruta) }}"
                        alt="{{ $tienda->tie_nombre }}">
                    @else
                    <div class="tp-fachada-empty">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                        </svg>
                    </div>
                    @endif
                    <span class="tp-badge-pendiente">Pendiente</span>
                </div>

                {{-- Info tienda --}}
                <div class="tp-body">
                    <div class="tp-section">
                        <p class="tp-section-label">Tienda</p>
                        <p class="tp-tienda-nombre">{{ $tienda->tie_nombre }}</p>
                        <p class="tp-tienda-dir">{{ $tienda->tie_direccion }}</p>
                        <p class="tp-tienda-tel">{{ $tienda->tie_telefono }}</p>
                        <p class="tp-tienda-fecha">
                            Solicitó el {{ $tienda->tie_fecha_registro->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <div class="tp-divider"></div>

                    {{-- Info usuario --}}
                    <div class="tp-section">
                        <p class="tp-section-label">Solicitante</p>
                        @php $persona = $tienda->user?->persona; @endphp
                        <p class="tp-user-nombre">
                            {{ $persona?->per_nombre }} {{ $persona?->per_paterno }} {{ $persona?->per_materno }}
                        </p>
                        <p class="tp-user-email">{{ $tienda->user?->email }}</p>
                        <p class="tp-user-tel">{{ $persona?->per_telefono ?? '—' }}</p>
                    </div>

                    <div class="tp-divider"></div>

                    {{-- Acciones --}}
                    <div class="tp-actions">
                        <a href="{{ \App\Filament\Pages\RevisionTienda::getUrl(['id' => $tienda->tie_id]) }}"
                            class="tp-btn-ver">
                            Ver documentos
                        </a>
                    </div>

                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>

    <style>
        .tp-wrap {
            font-family: 'Sora', sans-serif;
            padding: 0.25rem 0;
        }

        .tp-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }

        .tp-title {
            font-size: 1rem;
            font-weight: 800;
            color: #111;
        }

        .tp-count {
            display: inline-flex;
            align-items: center;
            background: #fff7e0;
            border: 1px solid #fcd34d;
            color: #92400e;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.2rem 0.65rem;
            border-radius: 999px;
            margin-left: 0.75rem;
        }

        .tp-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            padding: 3rem;
            text-align: center;
            color: #aaa;
        }

        .tp-empty svg {
            width: 48px;
            height: 48px;
            color: #c6f135;
        }

        .tp-empty p {
            font-size: 0.88rem;
        }

        .tp-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.25rem;
        }

        .tp-card {
            background: white;
            border-radius: 1rem;
            border: 1.5px solid #e8f5d0;
            overflow: hidden;
            transition: box-shadow 0.2s, border-color 0.2s;
        }

        .tp-card:hover {
            box-shadow: 0 8px 28px rgba(168, 223, 17, 0.15);
            border-color: #a8df11;
        }

        .tp-fachada {
            position: relative;
            height: 140px;
            background: #f8fdf0;
        }

        .tp-fachada img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .tp-fachada-empty {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .tp-fachada-empty svg {
            width: 48px;
            height: 48px;
            color: #c6f135;
        }

        .tp-badge-pendiente {
            position: absolute;
            top: 0.6rem;
            right: 0.6rem;
            background: #fff7e0;
            border: 1px solid #fcd34d;
            color: #92400e;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.2rem 0.65rem;
            border-radius: 999px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .tp-body {
            padding: 1rem;
        }

        .tp-section {
            margin-bottom: 0.75rem;
        }

        .tp-section-label {
            font-size: 0.6rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #7ab80e;
            margin-bottom: 0.35rem;
        }

        .tp-tienda-nombre {
            font-size: 0.95rem;
            font-weight: 800;
            color: #111;
            margin-bottom: 0.15rem;
        }

        .tp-tienda-dir,
        .tp-tienda-tel,
        .tp-tienda-fecha,
        .tp-user-email,
        .tp-user-tel {
            font-size: 0.75rem;
            color: #888;
            margin-bottom: 0.1rem;
        }

        .tp-user-nombre {
            font-size: 0.85rem;
            font-weight: 700;
            color: #111;
            margin-bottom: 0.15rem;
        }

        .tp-divider {
            height: 1px;
            background: #f0f0f0;
            margin: 0.75rem 0;
        }

        .tp-actions {
            display: flex;
            gap: 0.6rem;
        }

        .tp-btn-ver {
            flex: 1;
            display: block;
            text-align: center;
            background: #111;
            color: white;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0.65rem;
            border-radius: 0.65rem;
            text-decoration: none;
            transition: background 0.2s;
        }

        .tp-btn-ver:hover {
            background: #a8df11;
            color: #111;
        }
    </style>
</x-filament-widgets::widget>