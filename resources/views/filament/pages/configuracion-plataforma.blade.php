<x-filament-panels::page>

    <form wire:submit="guardar">
        {{ $this->form }}
        <div class="mt-6">
            <x-filament::button type="submit" icon="heroicon-o-check">
                Guardar cambios
            </x-filament::button>
        </div>
    </form>

    {{-- REPARTIDORES BLOQUEADOS --}}
    @php $bloqueados = $this->repartidoresBloqueados; @endphp
    @if ($bloqueados->count() > 0)
        <div style="margin-top:2rem;">
            <h3 style="font-size:.85rem;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:#d41b11;margin-bottom:1rem;">
                ⚠ Repartidores bloqueados por exceso de deuda ({{ $bloqueados->count() }})
            </h3>
            <div style="display:flex;flex-direction:column;gap:.75rem;">
                @foreach ($bloqueados as $rep)
                    @php
                        $persona = $rep->user?->persona;
                        $nombre  = trim(($persona?->per_nombre ?? '') . ' ' . ($persona?->per_paterno ?? '')) ?: 'Sin nombre';
                        $deuda   = \App\Services\RepartidorDeudaService::deudaTotal($rep);
                        $limite  = \App\Services\RepartidorDeudaService::limiteActual();
                    @endphp
                    <div style="background:#fff1f0;border:1.5px solid #fca5a5;border-radius:12px;padding:1rem 1.25rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                        <div>
                            <p style="font-size:.88rem;font-weight:700;color:#1a1a1a;">{{ $nombre }}</p>
                            <p style="font-size:.75rem;color:#888;">{{ $rep->user?->email }}</p>
                        </div>
                        <div style="text-align:right;">
                            <p style="font-size:.95rem;font-weight:900;color:#d41b11;">${{ number_format($deuda, 2) }}</p>
                            <p style="font-size:.7rem;color:#aaa;">Límite: ${{ number_format($limite, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</x-filament-panels::page>
