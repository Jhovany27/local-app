<x-filament-panels::page>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

@if(session('success'))
<div class="mt-success">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
    </svg>
    {{ session('success') }}
</div>
@endif

{{-- HEADER --}}
<div class="mt-header">
    <div>
        <p class="mt-kicker">Panel de tienda</p>
        <h1 class="mt-titulo">{{ $this->tienda->tie_nombre }}</h1>
    </div>
    <a href="{{ route('store.editar-tienda', session('store_tienda_id')) }}" class="mt-btn-editar">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
        </svg>
        Editar tienda
    </a>
</div>

<div class="mt-grid">

    {{-- COLUMNA IZQUIERDA --}}
    <div>

        {{-- DATOS --}}
        <div class="mt-card">
            <p class="mt-card-label">Datos de la tienda</p>
            <div class="mt-info-list">

                <div class="mt-info-row">
                    <div class="mt-info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72"/>
                        </svg>
                    </div>
                    <div class="mt-info-content">
                        <p class="mt-info-label">Nombre</p>
                        <p class="mt-info-val">{{ $this->tienda->tie_nombre }}</p>
                    </div>
                </div>

                <div class="mt-info-row">
                    <div class="mt-info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/>
                        </svg>
                    </div>
                    <div class="mt-info-content">
                        <p class="mt-info-label">Teléfono</p>
                        <p class="mt-info-val">{{ $this->tienda->tie_telefono }}</p>
                    </div>
                </div>

                <div class="mt-info-row">
                    <div class="mt-info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
                        </svg>
                    </div>
                    <div class="mt-info-content">
                        <p class="mt-info-label">Descripción</p>
                        <p class="mt-info-val">{{ $this->tienda->tie_descripcion }}</p>
                    </div>
                </div>

                <div class="mt-info-row">
                    <div class="mt-info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                    </div>
                    <div class="mt-info-content">
                        <p class="mt-info-label">Estado</p>
                        <span class="{{ $this->tienda->tie_estado ? 'mt-badge-activa' : 'mt-badge-inactiva' }}">
                            {{ $this->tienda->tie_estado ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>
                </div>

            </div>
        </div>

        {{-- FACHADA --}}
        <div class="mt-card">
            <p class="mt-card-label">Fachada</p>
            @if($this->tienda->fachada)
                <img src="{{ asset('storage/'.$this->tienda->fachada->fac_ruta) }}"
                     alt="Fachada"
                     class="mt-fachada-img">
            @else
                <div class="mt-fachada-empty">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z"/>
                    </svg>
                    <p>Sin fachada registrada</p>
                </div>
            @endif
        </div>

    </div>

    {{-- COLUMNA DERECHA --}}
    <div>

        {{-- UBICACIÓN --}}
        <div class="mt-card">
            <p class="mt-card-label">Ubicación</p>
            <div class="mt-mapa-wrap">
                <div id="mapa-tienda" class="mt-mapa"></div>
            </div>
            <div class="mt-dir-row">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                </svg>
                <p>{{ $this->tienda->tie_direccion }}</p>
            </div>
            <div class="mt-coords">
                <div class="mt-coord-box">
                    <span>Latitud</span>
                    <span>{{ $this->tienda->tie_latitud }}</span>
                </div>
                <div class="mt-coord-box">
                    <span>Longitud</span>
                    <span>{{ $this->tienda->tie_longitud }}</span>
                </div>
            </div>
        </div>

        {{-- DOCUMENTOS --}}
        <div class="mt-card">
            <p class="mt-card-label">Documentos</p>
            @if($this->tienda->documentos->count())
                <div class="mt-docs-list">
                    @foreach($this->tienda->documentos as $doc)
                    <div class="mt-doc-item">
                        <div class="mt-doc-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                            </svg>
                        </div>
                        <div class="mt-doc-info">
                            <p class="mt-doc-tipo">{{ $doc->tipo_documento_tienda?->tdt_nombre ?? 'Documento' }}</p>
                            <p class="mt-doc-fecha">{{ optional($doc->dot_fecha)->format('d/m/Y') }}</p>
                        </div>
                        <a href="{{ asset('storage/'.$doc->dot_ruta) }}"
                           target="_blank"
                           class="mt-doc-ver">
                            Ver PDF
                        </a>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="mt-empty-text">Sin documentos registrados.</p>
            @endif
        </div>

    </div>

</div>

{{-- NÚMERO DE CUENTA BANCARIA --}}
<div class="mt-card" style="margin-top:1.25rem;">
    <p class="mt-card-label">Número de cuenta bancaria</p>

    @if(session('cuenta_ok'))
        <div class="mt-success" style="margin-bottom:.85rem;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
            {{ session('cuenta_ok') }}
        </div>
    @endif

    @if ($this->tienda->tie_numero_cuenta)
        <div style="background:#f8fdf0;border:1px solid #d4edaa;border-radius:10px;padding:.65rem 1rem;margin-bottom:.85rem;display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#7ab80e;">Cuenta registrada</span>
            <span style="font-size:.88rem;font-weight:800;color:#1a1a1a;letter-spacing:.05em;">{{ $this->tienda->tie_numero_cuenta }}</span>
        </div>
    @endif

    {{-- Estado Stripe Connect --}}
    @if ($this->tienda->stripe_account_id)
        <div style="background:#f0fde0;border:1.5px solid #a8df11;border-radius:10px;padding:.7rem 1rem;margin-bottom:.85rem;display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#4a8a06" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                <span style="font-size:.82rem;font-weight:700;color:#4a8a06;">Cuenta Stripe conectada</span>
            </div>
            <a href="{{ route('store.stripe.onboarding') }}"
               style="font-size:.72rem;color:#888;text-decoration:underline;">Actualizar</a>
        </div>
    @else
        <a href="{{ route('store.stripe.onboarding') }}"
           style="display:flex;align-items:center;justify-content:center;gap:.5rem;width:100%;padding:.7rem;background:#635bff;border-radius:10px;color:#fff;font-size:.85rem;font-weight:700;text-decoration:none;margin-bottom:.85rem;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
            Conectar cuenta bancaria con Stripe
        </a>
    @endif

    <p style="font-size:.78rem;color:#888;margin-bottom:.85rem;line-height:1.4;">
        Este número se usará para realizarte los depósitos de tus liquidaciones. Usa tu CLABE interbancaria de 18 dígitos.
    </p>

    <form method="POST" action="{{ route('store.cuenta-bancaria') }}">
        @csrf
        <div style="display:flex;gap:.65rem;align-items:flex-end;">
            <div style="flex:1;">
                <label style="display:block;font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#7ab80e;margin-bottom:.3rem;">
                    {{ $this->tienda->tie_numero_cuenta ? 'Nueva cuenta' : 'Número de cuenta' }}
                </label>
                <input
                    type="text"
                    name="tie_numero_cuenta"
                    value="{{ old('tie_numero_cuenta') }}"
                    placeholder="CLABE 18 dígitos"
                    maxlength="18"
                    style="width:100%;padding:.6rem .85rem;border:1.5px solid #d1d5db;border-radius:9px;font-size:.88rem;font-family:'Sora',sans-serif;background:#f8fdf0;box-sizing:border-box;"
                    onfocus="this.style.borderColor='#a8df11'"
                    onblur="this.style.borderColor='#d1d5db'">
                @error('tie_numero_cuenta')
                    <span style="font-size:.72rem;color:#d41b11;">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit"
                style="padding:.62rem 1.15rem;background:linear-gradient(135deg,#a8df11,#7cc10a);border:none;border-radius:9px;font-family:'Sora',sans-serif;font-size:.82rem;font-weight:800;color:#1a1a1a;cursor:pointer;white-space:nowrap;flex-shrink:0;">
                Guardar
            </button>
        </div>
    </form>
</div>

<style>
/* Wrap */
.mt-success { display: flex; align-items: center; gap: 0.5rem; background: #f0fde0; border: 1px solid #c6f135; color: #4a8a06; font-size: 0.82rem; font-weight: 600; padding: 0.65rem 1rem; border-radius: 0.75rem; margin-bottom: 1.25rem; }
.mt-success svg { width: 16px; height: 16px; flex-shrink: 0; }

/* Header */
.mt-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.75rem; }
.mt-kicker { font-size: 0.65rem; font-weight: 800; letter-spacing: 0.14em; text-transform: uppercase; color: #7ab80e; margin-bottom: 0.25rem; }
.mt-titulo { font-size: 1.5rem; font-weight: 900; color: #111; }
.mt-btn-editar { display: inline-flex; align-items: center; gap: 0.5rem; background: linear-gradient(135deg, #a8df11, #7cc10a); color: #1a1a1a; font-size: 0.85rem; font-weight: 700; padding: 0.65rem 1.25rem; border-radius: 0.75rem; text-decoration: none; box-shadow: 0 4px 14px rgba(168,223,17,0.3); transition: opacity 0.2s; }
.mt-btn-editar svg { width: 16px; height: 16px; }
.mt-btn-editar:hover { opacity: 0.9; }

/* Grid */
.mt-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; align-items: start; }
@media (max-width: 768px) { .mt-grid { grid-template-columns: 1fr; } }

/* Card */
.mt-card { background: white; border: 1.5px solid #e8f5d0; border-radius: 1rem; padding: 1.25rem; margin-bottom: 1.25rem; }
.mt-card-label { font-size: 0.62rem; font-weight: 800; letter-spacing: 0.14em; text-transform: uppercase; color: #7ab80e; margin-bottom: 1rem; }

/* Info list */
.mt-info-list { display: flex; flex-direction: column; gap: 0.75rem; }
.mt-info-row { display: flex; align-items: flex-start; gap: 0.75rem; }
.mt-info-icon { width: 32px; height: 32px; border-radius: 0.6rem; background: #f0fde0; border: 1px solid #d4f0a0; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.mt-info-icon svg { width: 15px; height: 15px; color: #4a8a06; }
.mt-info-content { flex: 1; }
.mt-info-label { font-size: 0.62rem; font-weight: 700; color: #bbb; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.15rem; }
.mt-info-val { font-size: 0.85rem; font-weight: 600; color: #111; line-height: 1.4; }

/* Badges */
.mt-badge-activa { display: inline-flex; align-items: center; gap: 0.3rem; background: #f0fde0; border: 1.5px solid #a8df11; color: #4a8a06; font-size: 0.72rem; font-weight: 700; padding: 0.2rem 0.65rem; border-radius: 999px; }
.mt-badge-inactiva { display: inline-flex; align-items: center; gap: 0.3rem; background: #fff1f0; border: 1.5px solid #fca5a5; color: #d41b11; font-size: 0.72rem; font-weight: 700; padding: 0.2rem 0.65rem; border-radius: 999px; }

/* Fachada */
.mt-fachada-img { width: 100%; height: 200px; object-fit: cover; border-radius: 0.75rem; }
.mt-fachada-empty { width: 100%; height: 160px; background: #f8fdf0; border-radius: 0.75rem; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem; color: #aaa; font-size: 0.78rem; }
.mt-fachada-empty svg { width: 40px; height: 40px; color: #c6f135; }

/* Mapa */
.mt-mapa-wrap { border-radius: 0.75rem; overflow: hidden; border: 1.5px solid #e8f5d0; margin-bottom: 0.85rem; }
.mt-mapa { width: 100%; height: 220px; z-index: 1; }
.mt-dir-row { display: flex; align-items: flex-start; gap: 0.5rem; font-size: 0.82rem; color: #555; margin-bottom: 0.75rem; }
.mt-dir-row svg { width: 16px; height: 16px; color: #a8df11; flex-shrink: 0; margin-top: 1px; }
.mt-coords { display: flex; gap: 0.5rem; }
.mt-coord-box { flex: 1; background: #f8fdf0; border: 1px solid #e8f5d0; border-radius: 0.65rem; padding: 0.5rem 0.75rem; text-align: center; }
.mt-coord-box span:first-child { display: block; font-size: 0.6rem; color: #aaa; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.1rem; }
.mt-coord-box span:last-child { font-size: 0.78rem; font-weight: 700; color: #4a8a06; }

/* Documentos */
.mt-docs-list { display: flex; flex-direction: column; gap: 0.65rem; }
.mt-doc-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8fdf0; border: 1px solid #e8f5d0; border-radius: 0.75rem; }
.mt-doc-icon { width: 34px; height: 34px; border-radius: 0.55rem; background: white; border: 1px solid #d4f0a0; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.mt-doc-icon svg { width: 16px; height: 16px; color: #7ab80e; }
.mt-doc-info { flex: 1; }
.mt-doc-tipo { font-size: 0.82rem; font-weight: 700; color: #111; }
.mt-doc-fecha { font-size: 0.68rem; color: #aaa; margin-top: 0.1rem; }
.mt-doc-ver { font-size: 0.75rem; font-weight: 700; color: #4a8a06; background: white; border: 1.5px solid #d4f0a0; border-radius: 0.5rem; padding: 0.3rem 0.75rem; text-decoration: none; flex-shrink: 0; transition: background 0.15s; }
.mt-doc-ver:hover { background: #f0fde0; }

.mt-empty-text { font-size: 0.82rem; color: #aaa; }
</style>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const lat = {{ floatval($this->tienda->tie_latitud) ?: 17.9869 }};
    const lng = {{ floatval($this->tienda->tie_longitud) ?: -92.9303 }};

    const mapa = L.map('mapa-tienda', {
        zoomControl: false,
        dragging: false,
        scrollWheelZoom: false,
        doubleClickZoom: false,
        touchZoom: false,
        attributionControl: false,
    }).setView([lat, lng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapa);

    const pinSvg = '<svg width="32" height="32" viewBox="0 0 24 24" fill="none">'
        + '<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="#a8df11" stroke="white" stroke-width="1.5"/>'
        + '<circle cx="12" cy="9" r="2.5" fill="white"/>'
        + '</svg>';

    const pinIcon = L.divIcon({
        className: '',
        html: pinSvg,
        iconSize: [32, 32],
        iconAnchor: [16, 32],
    });

    L.marker([lat, lng], { icon: pinIcon }).addTo(mapa);
</script>

</x-filament-panels::page>