<x-filament-panels::page>

    <div class="sel-root">

        <div style="margin-bottom:1.75rem;">
            <p class="sel-header-kicker">Panel de tienda</p>
            <h1 class="sel-header-title">Selecciona tu tienda</h1>
            <p class="sel-header-sub">Elige la tienda que vas a administrar hoy.</p>
        </div>

        <div class="sel-grid">
            @foreach ($this->tiendas as $tienda)
                <div class="sel-card">
                    <div class="sel-card-accent"></div>
                    <div class="sel-card-body">

                        <div class="sel-card-header">
                            <div>
                                <p class="sel-card-id">Tienda #{{ $tienda->tie_id }}</p>
                                <h2 class="sel-card-name">{{ $tienda->tie_nombre }}</h2>
                            </div>
                            <span class="sel-badge {{ $tienda->tie_estado == \App\Models\Tienda::ESTADO_APROBADA ? 'sel-badge-active' : 'sel-badge-inactive' }}">
                                <span class="sel-badge-dot"></span>
                                @if ($tienda->tie_estado == \App\Models\Tienda::ESTADO_APROBADA)
                                    Activa
                                @elseif ($tienda->tie_estado == \App\Models\Tienda::ESTADO_PENDIENTE)
                                    Pendiente
                                @else
                                    Rechazada
                                @endif
                            </span>
                        </div>

                        <div class="sel-info-row">
                            <p class="sel-info-label">Teléfono</p>
                            <p class="sel-info-value">{{ $tienda->tie_telefono ?: '—' }}</p>
                        </div>
                        <div class="sel-info-row">
                            <p class="sel-info-label">Dirección</p>
                            <p class="sel-info-value">{{ $tienda->tie_direccion ?: '—' }}</p>
                        </div>

                        @if ($tienda->tie_estado == \App\Models\Tienda::ESTADO_APROBADA)
                            <button type="button" wire:click="seleccionar({{ $tienda->tie_id }})"
                                wire:loading.attr="disabled"
                                class="sel-btn sel-btn-active"
                                style="margin-top:1rem;">
                                <span>Entrar a esta tienda</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" style="width:15px;height:15px;">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                        @else
                            <button type="button" wire:click="verEstado({{ $tienda->tie_id }})"
                                wire:loading.attr="disabled"
                                class="sel-btn sel-btn-estado"
                                style="margin-top:1rem;">
                                <span>
                                    {{ $tienda->tie_estado == \App\Models\Tienda::ESTADO_PENDIENTE ? 'Ver estado (Pendiente)' : 'Ver motivo de rechazo' }}
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" style="width:15px;height:15px;">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.573-3.007-9.964-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        @endif

                    </div>
                </div>
            @endforeach
        </div>

    </div>

</x-filament-panels::page>
