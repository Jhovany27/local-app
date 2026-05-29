<x-filament-panels::page>

<div class="nv-root">
    <div class="nv-layout">

        {{-- COLUMNA IZQUIERDA: buscador + resultados --}}
        <div class="nv-left">

            {{-- Buscador --}}
            <div class="nv-search-box">
                <div class="nv-search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                </div>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="busqueda"
                    placeholder="Buscar producto por nombre..."
                    class="nv-search-input"
                    autocomplete="off"
                />
                @if($busqueda)
                <button wire:click="$set('busqueda', '')" class="nv-search-clear">✕</button>
                @endif
            </div>

            {{-- Resultados --}}
            @if(count($resultados))
            <div class="nv-results">
                @foreach($resultados as $producto)
                <button
                    type="button"
                    wire:click="agregarProducto({{ $producto['pro_id'] }})"
                    class="nv-result-item"
                >
                    <div class="nv-result-info">
                        <span class="nv-result-name">{{ $producto['pro_nombre'] }}</span>
                        <span class="nv-result-marca">{{ $producto['pro_marca'] }}</span>
                    </div>
                    <div class="nv-result-right">
                        <span class="nv-result-price">${{ number_format($producto['pro_precio_venta'], 2) }}</span>
                        <span class="nv-result-stock">Stock: {{ $producto['inventario']['inv_stock_actual'] ?? 0 }}</span>
                    </div>
                </button>
                @endforeach
            </div>

            @elseif(strlen($busqueda) >= 2)
            <div class="nv-empty-search">
                No se encontraron productos con "<strong>{{ $busqueda }}</strong>"
            </div>

            @else
            {{-- Estado vacío con más presencia --}}
            <div class="nv-hint">
                <div class="nv-hint-icon-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                </div>
                <span class="nv-hint-title">Busca un producto</span>
                <p>Escribe el nombre de un producto para buscarlo y agregarlo a la venta</p>
            </div>
            @endif

        </div>

        {{-- COLUMNA DERECHA: carrito --}}
        <div class="nv-right">

            <div class="nv-cart-header">
                <h2 class="nv-cart-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c.51 0 .961-.305 1.139-.767l2.25-6A1.125 1.125 0 0018.75 6H5.372M7.5 14.25L5.106 5.272M7.5 14.25l-1.5 1.5M16.5 18.75a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm-9 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                    </svg>
                    Productos en venta
                </h2>
                @if(count($carrito))
                <button wire:click="limpiarCarrito" class="nv-clear-btn">Limpiar todo</button>
                @endif
            </div>

            @if(count($carrito))

            <div class="nv-cart-list">
                <div class="nv-cart-thead">
                    <span>Producto</span>
                    <span>Precio</span>
                    <span>Cantidad</span>
                    <span>Subtotal</span>
                    <span></span>
                </div>

                @foreach($carrito as $i => $item)
                <div class="nv-cart-row">
                    <div class="nv-cart-product">
                        <span class="nv-cart-name">{{ $item['pro_nombre'] }}</span>
                        <span class="nv-cart-brand">{{ $item['pro_marca'] }}</span>
                    </div>
                    <span class="nv-cart-price">${{ number_format($item['pro_precio_venta'], 2) }}</span>
                    <div class="nv-qty-control">
                        <button wire:click="decrementar({{ $i }})" class="nv-qty-btn">−</button>
                        <span class="nv-qty-value">{{ $item['cantidad'] }}</span>
                        <button wire:click="incrementar({{ $i }})" class="nv-qty-btn">+</button>
                    </div>
                    <span class="nv-cart-subtotal">${{ number_format($item['subtotal'], 2) }}</span>
                    <button wire:click="eliminar({{ $i }})" class="nv-delete-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                @endforeach
            </div>

            <div class="nv-cart-footer">
                <div class="nv-total-row">
                    <span class="nv-total-label">Total</span>
                    <span class="nv-total-value">${{ number_format($this->getTotal(), 2) }}</span>
                </div>
                <button
                    wire:click="confirmarVenta"
                    wire:loading.attr="disabled"
                    class="nv-confirm-btn"
                >
                    <span wire:loading.remove>Confirmar venta</span>
                    <span wire:loading>Registrando...</span>
                </button>
            </div>

            @else
            {{-- Carrito vacío con más presencia --}}
            <div class="nv-cart-empty">
                <div class="nv-cart-empty-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c.51 0 .961-.305 1.139-.767l2.25-6A1.125 1.125 0 0018.75 6H5.372M7.5 14.25L5.106 5.272"/>
                    </svg>
                </div>
                <p>No hay productos en la venta</p>
                <span>Busca y agrega productos desde el panel izquierdo</span>
            </div>
            @endif

        </div>
    </div>
</div>

</x-filament-panels::page>