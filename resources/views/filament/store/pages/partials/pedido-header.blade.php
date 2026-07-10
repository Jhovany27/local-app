{{-- resources/views/filament/store/pages/partials/pedido-header.blade.php --}}
<div class="ped-card-header">
    <div>
        <p class="ped-codigo">#{{ $pedido->ped_codigo }}</p>
        <p class="ped-fecha">{{ $pedido->ped_fecha_pedido->format('d/m/Y H:i') }}</p>
    </div>
    <div style="text-align:right">
        @php $persona = $pedido->cliente?->user?->persona; @endphp
        <p class="ped-cliente">
            {{ $persona?->per_nombre }} {{ $persona?->per_paterno }}
        </p>
        <p class="ped-entrega">
            {{ strtolower($pedido->ped_tipo_entrega) === 'domicilio' ? '🛵 Domicilio' : '🏪 Recoger' }}
        </p>
    </div>
</div>