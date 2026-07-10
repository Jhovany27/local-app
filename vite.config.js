import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/filament/store/theme.css',
                // cliente
                'resources/css/cliente/index.css',
                'resources/css/cliente/tienda.css',
                'resources/css/cliente/producto.css',
                'resources/css/cliente/carrito.css',
                'resources/css/cliente/checkout.css',
                'resources/css/cliente/mis-pedidos.css',
                'resources/css/cliente/favoritos.css',
                'resources/css/cliente/perfil.css',
                'resources/css/cliente/tarjetas.css',
                'resources/css/cliente/direcciones/create.css',
                'resources/css/cliente/direcciones/edit.css',
                'resources/css/cliente/direcciones/index.css',
                'resources/css/cliente/direcciones/show.css',
                // repartidor
                'resources/css/repartidor/index.css',
                'resources/css/repartidor/pedido.css',
                'resources/css/repartidor/entregar.css',
                'resources/css/repartidor/en-camino.css',
                'resources/css/repartidor/checklist.css',
                'resources/css/repartidor/historial.css',
                'resources/css/repartidor/perfil.css',
                // tienda
                'resources/css/tienda/editar.css',
                // js
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});