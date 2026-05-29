<x-filament-panels::page>

<style>
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&display=swap');

.sel-root {
    font-family: 'Sora', sans-serif;
    padding: 0.5rem 0 2rem;
}

/* ── HERO ── */
.sel-hero {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #a8df11 0%, #6db800 60%, #4a9000 100%);
    border-radius: 1.5rem;
    padding: 3rem 2.5rem;
    margin-bottom: 2.5rem;
    box-shadow: 0 20px 60px rgba(168,223,17,0.25);
}

.sel-hero::before {
    content: '';
    position: absolute;
    top: -80px; right: -80px;
    width: 300px; height: 300px;
    border-radius: 50%;
    background: rgba(255,255,255,0.07);
    pointer-events: none;
}

.sel-hero::after {
    content: '';
    position: absolute;
    bottom: -60px; left: 30%;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
    pointer-events: none;
}

.sel-hero-inner {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
    flex-wrap: wrap;
}

.sel-hero-text {}

.sel-kicker {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    border-radius: 999px;
    padding: 0.3rem 1rem;
    margin-bottom: 1rem;
    backdrop-filter: blur(4px);
}

.sel-kicker-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: white;
    animation: sel-blink 2s ease-in-out infinite;
}

@keyframes sel-blink {
    0%,100% { opacity: 1; }
    50% { opacity: 0.3; }
}

.sel-hero h1 {
    font-size: clamp(1.6rem, 3vw, 2.4rem);
    font-weight: 800;
    color: white;
    line-height: 1.2;
    margin: 0 0 0.65rem;
}

.sel-hero p {
    color: rgba(255,255,255,0.85);
    font-size: 0.9rem;
    line-height: 1.7;
    max-width: 520px;
    font-weight: 400;
}

.sel-hero-icon {
    width: 80px; height: 80px;
    border-radius: 1.25rem;
    background: rgba(255,255,255,0.15);
    border: 1.5px solid rgba(255,255,255,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    backdrop-filter: blur(8px);
}

.sel-hero-icon svg {
    width: 40px; height: 40px;
    color: white;
}

/* ── GRID ── */
.sel-grid {
    display: grid;
    gap: 1.5rem;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
}

/* ── CARD ── */
.sel-card {
    background: white;
    border-radius: 1.25rem;
    border: 1.5px solid #e8f5d0;
    overflow: hidden;
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    animation: sel-fadein 0.4s ease both;
}

@keyframes sel-fadein {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}

.sel-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 50px rgba(168,223,17,0.18), 0 4px 16px rgba(0,0,0,0.06);
    border-color: #a8df11;
}

.sel-card-accent {
    height: 4px;
    background: linear-gradient(90deg, #a8df11, #6db800);
}

.sel-card-body {
    padding: 1.5rem;
}

/* Header */
.sel-card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.25rem;
}

.sel-card-id {
    font-size: 0.65rem;
    font-weight: 800;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: #7ab80e;
    margin-bottom: 0.3rem;
}

.sel-card-name {
    font-size: 1.2rem;
    font-weight: 800;
    color: #111;
    line-height: 1.25;
}

/* Badge */
.sel-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    border-radius: 999px;
    padding: 0.3rem 0.75rem;
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    flex-shrink: 0;
}

.sel-badge-dot {
    width: 5px; height: 5px;
    border-radius: 50%;
}

.sel-badge-active {
    background: #f0fde0;
    color: #4a8a06;
    border: 1px solid #c6f135;
}

.sel-badge-active .sel-badge-dot { background: #6db800; }

.sel-badge-inactive {
    background: #fff1f0;
    color: #c0392b;
    border: 1px solid #fca5a5;
}

.sel-badge-inactive .sel-badge-dot { background: #d41b11; }

/* Divider */
.sel-divider {
    height: 1px;
    background: #f0f0f0;
    margin: 1rem 0;
}

/* Info rows */
.sel-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
    margin-bottom: 1.25rem;
}

.sel-info-box {
    background: #f8fdf0;
    border: 1px solid #e8f5d0;
    border-radius: 0.75rem;
    padding: 0.75rem;
}

.sel-info-label {
    font-size: 0.6rem;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: #7ab80e;
    margin-bottom: 0.2rem;
}

.sel-info-value {
    font-size: 0.82rem;
    font-weight: 600;
    color: #1a1a1a;
    line-height: 1.4;
}

.sel-desc-box {
    background: #f8fdf0;
    border: 1px solid #e8f5d0;
    border-radius: 0.75rem;
    padding: 0.75rem;
    margin-bottom: 1rem;
}

.sel-desc-label {
    font-size: 0.6rem;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: #7ab80e;
    margin-bottom: 0.2rem;
}

.sel-desc-text {
    font-size: 0.82rem;
    color: #444;
    line-height: 1.6;
}

/* Button */
.sel-btn {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    background: #111;
    color: white;
    font-family: 'Sora', sans-serif;
    font-size: 0.85rem;
    font-weight: 700;
    padding: 0.85rem 1.5rem;
    border-radius: 0.85rem;
    border: none;
    cursor: pointer;
    transition: background 0.2s ease, transform 0.15s ease, box-shadow 0.2s ease;
    letter-spacing: 0.02em;
}

.sel-btn:hover:not(:disabled) {
    background: #a8df11;
    color: #111;
    transform: translateY(-1px);
    box-shadow: 0 8px 24px rgba(168,223,17,0.35);
}

.sel-btn:active:not(:disabled) {
    transform: translateY(0);
}

.sel-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.sel-btn svg {
    width: 16px; height: 16px;
    transition: transform 0.2s ease;
}

.sel-btn:hover:not(:disabled) svg {
    transform: translateX(3px);
}

/* Stagger cards */
.sel-card:nth-child(1) { animation-delay: 0.05s; }
.sel-card:nth-child(2) { animation-delay: 0.12s; }
.sel-card:nth-child(3) { animation-delay: 0.19s; }
.sel-card:nth-child(4) { animation-delay: 0.26s; }
.sel-card:nth-child(5) { animation-delay: 0.33s; }
.sel-card:nth-child(6) { animation-delay: 0.40s; }
</style>

<div class="sel-root">

    {{-- HERO --}}
    <div class="sel-hero">
        <div class="sel-hero-inner">
            <div class="sel-hero-text">
                <div class="sel-kicker">
                    <span class="sel-kicker-dot"></span>
                    Panel de tienda
                </div>
                <h1>Selecciona tu tienda</h1>
                <p>Elige la tienda que vas a administrar hoy para gestionar sus productos, pedidos e inventario.</p>
            </div>
            <div class="sel-hero-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- GRID DE TIENDAS --}}
    <div class="sel-grid">
        @foreach ($this->tiendas as $tienda)
        <div class="sel-card">
            <div class="sel-card-accent"></div>
            <div class="sel-card-body">

                {{-- Header --}}
                <div class="sel-card-header">
                    <div>
                        <p class="sel-card-id">Tienda #{{ $tienda->tie_id }}</p>
                        <h2 class="sel-card-name">{{ $tienda->tie_nombre }}</h2>
                    </div>
                    <span class="sel-badge {{ $tienda->tie_estado ? 'sel-badge-active' : 'sel-badge-inactive' }}">
                        <span class="sel-badge-dot"></span>
                        {{ $tienda->tie_estado ? 'Activa' : 'Inactiva' }}
                    </span>
                </div>

                {{-- Descripción --}}
                <div class="sel-desc-box">
                    <p class="sel-desc-label">Descripción</p>
                    <p class="sel-desc-text">{{ $tienda->tie_descripcion ?: 'Sin descripción disponible.' }}</p>
                </div>

                {{-- Info --}}
                <div class="sel-info-grid">
                    <div class="sel-info-box">
                        <p class="sel-info-label">Teléfono</p>
                        <p class="sel-info-value">{{ $tienda->tie_telefono ?: 'No registrado' }}</p>
                    </div>
                    <div class="sel-info-box">
                        <p class="sel-info-label">Dirección</p>
                        <p class="sel-info-value">{{ $tienda->tie_direccion ?: 'No registrada' }}</p>
                    </div>
                </div>

                {{-- Botón --}}
                <button
                    type="button"
                    wire:click="seleccionar({{ $tienda->tie_id }})"
                    wire:loading.attr="disabled"
                    class="sel-btn"
                >
                    <span>Entrar a esta tienda</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </button>

            </div>
        </div>
        @endforeach
    </div>

</div>

</x-filament-panels::page>