<?php

use App\Http\Controllers\AuthController;

use App\Http\Controllers\ClienteController;

use App\Http\Controllers\EditarTiendaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\RegistroTiendaController;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/', function () {
    return redirect()->route('portal');
});
Route::get('/portal', [HomeController::class, 'portal'])->name('portal');

Route::get('/store/cambiar-tienda', function () {
    session()->forget('store_tienda_id');
    return redirect()->route('filament.store.pages.seleccionar-tienda');
})->name('store.cambiar-tienda');

Route::get('/registro-tienda', [RegistroTiendaController::class, 'index'])
    ->name('registro.tienda');

Route::post('/registro-tienda', [RegistroTiendaController::class, 'store'])
    ->name('registro.tienda.store');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/registro-tienda/pendiente', [RegistroTiendaController::class, 'pendiente'])
        ->name('registro.tienda.pendiente');

    Route::get('/tienda/{tienda}/estado', [RegistroTiendaController::class, 'verEstado'])
        ->name('tienda.estado');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/store/editar-tienda', [EditarTiendaController::class, 'edit'])
        ->name('store.editar-tienda');

    Route::post('/store/editar-tienda', [EditarTiendaController::class, 'update']);
});


Route::get('/registro', [RegistroController::class, 'create'])->name('registro.create');
Route::post('/registro', [RegistroController::class, 'store'])->name('registro.store');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =======================
// CLIENTE AUTH
// =======================
Route::get('/cliente/login', [\App\Http\Controllers\ClienteAuthController::class, 'showLogin'])
    ->name('cliente.login');

Route::post('/cliente/login', [\App\Http\Controllers\ClienteAuthController::class, 'login'])
    ->name('cliente.login.store')->middleware('throttle:5,1');

Route::get('/cliente/registro', [\App\Http\Controllers\ClienteAuthController::class, 'showRegistro'])
    ->name('cliente.registro');

Route::post('/cliente/registro', [\App\Http\Controllers\ClienteAuthController::class, 'registro'])
    ->name('cliente.registro.store');

Route::get('/cliente/pedido', [\App\Http\Controllers\ClienteController::class, 'pedido'])
    ->name('cliente.pedido');

Route::post('/cliente/logout', [\App\Http\Controllers\ClienteAuthController::class, 'logout'])
    ->name('cliente.logout');

Route::get('/cliente', [ClienteController::class, 'index'])->name('cliente.index');

Route::get('/cliente/tienda/{id}', [ClienteController::class, 'show'])
    ->name('cliente.tienda');
Route::get('/cliente/producto/{id}', [ClienteController::class, 'showProducto'])
    ->name('cliente.producto');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/cliente/perfil', [\App\Http\Controllers\ClienteAuthController::class, 'perfil'])
        ->name('cliente.perfil');
});

// =======================
// DIRECCIONES AUTH
// =======================

// Reemplaza las rutas de direcciones en web.php

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/cliente/direcciones',                  [\App\Http\Controllers\DireccionController::class, 'index'])
        ->name('cliente.direcciones');

    Route::get('/cliente/direcciones/nueva',            [\App\Http\Controllers\DireccionController::class, 'create'])
        ->name('cliente.direcciones.create');

    Route::post('/cliente/direcciones',                 [\App\Http\Controllers\DireccionController::class, 'store'])
        ->name('cliente.direcciones.store');

    Route::get('/cliente/direcciones/{id}',             [\App\Http\Controllers\DireccionController::class, 'show'])
        ->name('cliente.direcciones.show');

    Route::get('/cliente/direcciones/{id}/editar',      [\App\Http\Controllers\DireccionController::class, 'edit'])
        ->name('cliente.direcciones.edit');

    Route::put('/cliente/direcciones/{id}',             [\App\Http\Controllers\DireccionController::class, 'update'])
        ->name('cliente.direcciones.update');

    Route::delete('/cliente/direcciones/{id}',          [\App\Http\Controllers\DireccionController::class, 'destroy'])
        ->name('cliente.direcciones.destroy');

    Route::post('/cliente/direcciones/{id}/seleccionar', [\App\Http\Controllers\DireccionController::class, 'seleccionar'])
        ->name('cliente.direcciones.seleccionar');
});
// =======================
// CARRITO (SESSION)
// =======================
Route::post('/carrito/agregar/{producto}', [\App\Http\Controllers\CarritoController::class, 'agregar'])
    ->name('carrito.agregar');

Route::post('/carrito/sumar',   [\App\Http\Controllers\CarritoController::class, 'sumar'])
    ->name('carrito.sumar');

Route::post('/carrito/restar',  [\App\Http\Controllers\CarritoController::class, 'restar'])
    ->name('carrito.restar');

Route::post('/carrito/eliminar', [\App\Http\Controllers\CarritoController::class, 'eliminar'])
    ->name('carrito.eliminar');

Route::get('/carrito', [\App\Http\Controllers\CarritoController::class, 'index'])
    ->name('carrito.index');

// Estas SÍ requieren login — el controlador las maneja internamente
Route::get('/carrito/checkout/{pedidoId}', [\App\Http\Controllers\CarritoController::class, 'checkout'])
    ->name('carrito.checkout');

Route::post('/carrito/confirmar/{pedidoId}', [\App\Http\Controllers\CarritoController::class, 'confirmar'])
    ->name('carrito.confirmar');

Route::get('/cliente/pedidos', [\App\Http\Controllers\CarritoController::class, 'misPedidos'])
    ->middleware('auth')
    ->name('cliente.pedidos');

// FAVORITOS
Route::middleware(['auth'])->group(function () {
    Route::post('/cliente/favorito/producto/agregar', [ClienteController::class, 'agregarFavoritoProducto'])
        ->name('cliente.favorito.producto.agregar');

    Route::post('/cliente/favorito/producto/quitar', [ClienteController::class, 'quitarFavoritoProducto'])
        ->name('cliente.favorito.producto.quitar');

    Route::post('/cliente/favorito/tienda/agregar', [ClienteController::class, 'agregarFavoritoTienda'])
        ->name('cliente.favorito.tienda.agregar');

    Route::post('/cliente/favorito/tienda/quitar', [ClienteController::class, 'quitarFavoritoTienda'])
        ->name('cliente.favorito.tienda.quitar');

    Route::get('/cliente/favoritos', [ClienteController::class, 'favoritos'])
        ->name('cliente.favoritos');
});

Route::get('/store', function () {
    /** @var \App\Models\User $user */
    $user = Auth::user();

    //  Sin rol tienda → revisar estado
    if (!$user->hasRol('tienda')) {
        if ($user->tiendaPendiente()) {
            return redirect()->route('registro.tienda.pendiente');
        }
        return redirect()->route('registro.tienda');
    }

    // Con rol tienda → lógica de selección
    $tiendas = $user->tiendas()
        ->where('tie_estado', \App\Models\Tienda::ESTADO_APROBADA)
        ->get();

    if ($tiendas->isEmpty()) {
        return redirect()->route('registro.tienda');
    }

    if ($tiendas->count() === 1) {
        session(['store_tienda_id' => $tiendas->first()->tie_id]);
        return redirect()->route('filament.store.pages.dashboard');
    }

    if (!session()->has('store_tienda_id')) {
        return redirect()->route('filament.store.pages.seleccionar-tienda');
    }

    return redirect()->route('filament.store.pages.dashboard');
})->name('store.home')->middleware('auth');

// Reemplaza todas las rutas del repartidor en web.php

// ── AUTH (sin middleware) ─────────────────────────────
Route::get('/driver/login',     [\App\Http\Controllers\RepartidorAuthController::class, 'showLogin'])->name('repartidor.login');
Route::post('/driver/login',    [\App\Http\Controllers\RepartidorAuthController::class, 'login'])->name('repartidor.login.store')->middleware('throttle:5,1');
Route::get('/driver/registro',  [\App\Http\Controllers\RepartidorAuthController::class, 'showRegistro'])->name('repartidor.registro');
Route::post('/driver/registro', [\App\Http\Controllers\RepartidorAuthController::class, 'registro'])->name('repartidor.registro.store');

// ── PANEL (con auth) ──────────────────────────────────
Route::middleware(['auth'])->prefix('driver')->group(function () {

    Route::post('/logout',   [\App\Http\Controllers\RepartidorAuthController::class, 'logout'])->name('repartidor.logout');
    Route::get('/pendiente', [\App\Http\Controllers\RepartidorAuthController::class, 'pendiente'])->name('repartidor.pendiente');
    Route::get('/completar-perfil',  [\App\Http\Controllers\RepartidorAuthController::class, 'showCompletarPerfil'])->name('repartidor.completar-perfil');
    Route::post('/completar-perfil', [\App\Http\Controllers\RepartidorAuthController::class, 'completarPerfil'])->name('repartidor.completar-perfil.store');

    Route::get('/perfil', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $persona = $user->persona;
        $repartidor = $user->repartidors()->firstOrFail();
        $fotoPerfil = $repartidor->documentos()->where('dor_fk_tipo_documento', 4)->first();
        return view('repartidor.perfil', compact('user', 'persona', 'repartidor', 'fotoPerfil'));
    })->name('repartidor.perfil');
    Route::post('/zona',     [\App\Http\Controllers\RepartidorController::class, 'actualizarZona'])->name('repartidor.zona.update');
    Route::get('/historial', [\App\Http\Controllers\RepartidorController::class, 'historial'])->name('repartidor.historial');

    Route::get('/',                          [\App\Http\Controllers\RepartidorController::class, 'index'])->name('repartidor.index');
    Route::get('/pedido/{pedidoId}',         [\App\Http\Controllers\RepartidorController::class, 'show'])->name('repartidor.pedido');
    Route::post('/pedido/{pedidoId}/aceptar', [\App\Http\Controllers\RepartidorController::class, 'aceptar'])->name('repartidor.aceptar');
    Route::get('/pedido/{pedidoId}/en-camino', [\App\Http\Controllers\RepartidorController::class, 'enCamino'])->name('repartidor.en-camino');
    Route::post('/pedido/{pedidoId}/llegue-tienda', [\App\Http\Controllers\RepartidorController::class, 'llegueATienda'])->name('repartidor.llegue-tienda');
    Route::get('/pedido/{pedidoId}/checklist', [\App\Http\Controllers\RepartidorController::class, 'checklist'])->name('repartidor.checklist');
    Route::post('/pedido/{pedidoId}/recogi', [\App\Http\Controllers\RepartidorController::class, 'recogiPedido'])->name('repartidor.recogi-pedido');
    Route::get('/pedido/{pedidoId}/entregar', [\App\Http\Controllers\RepartidorController::class, 'entregar'])->name('repartidor.entregar');
    Route::post('/pedido/{pedidoId}/entregue', [\App\Http\Controllers\RepartidorController::class, 'entreguePedido'])->name('repartidor.entregue-pedido');
});


// ── STRIPE ────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::post('/stripe/intent',    [\App\Http\Controllers\StripeController::class, 'crearIntent'])
        ->name('stripe.intent');
    Route::post('/stripe/confirmar', [\App\Http\Controllers\StripeController::class, 'confirmar'])
        ->name('stripe.confirmar');
});

// Webhook — sin auth ni CSRF (ya está excluido en bootstrap/app.php)
Route::post('/stripe/webhook', [\App\Http\Controllers\StripeController::class, 'webhook'])
    ->name('stripe.webhook');

// Páginas "verifica tu correo" por rol
Route::get('/email/verify', fn() => view('auth.verify-email'))
    ->middleware('auth')->name('verification.notice'); // cliente

Route::get('/tienda/email/verify', fn() => view('tienda.auth.verify-email'))
    ->middleware('auth')->name('tienda.verification.notice');

Route::get('/repartidor/email/verify', fn() => view('repartidor.auth.verify-email'))
    ->middleware('auth')->name('repartidor.verification.notice');

// Link del correo — redirige según rol del usuario
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    /** @var \App\Models\User $user */
    $user = $request->user();

    if ($user->hasRol('cliente')) {
        return redirect()->route('cliente.index');
    }

    if ($user->hasRol('repartidor')) {
        $rep = $user->repartidors()->first();
        return ($rep && (int)$rep->rep_estado === 1)
            ? redirect()->route('repartidor.index')
            : redirect()->route('repartidor.pendiente');
    }

    // Flujo tienda (sin rol aún hasta aprobación del admin)
    return $user->tiendas()->exists()
        ? redirect()->route('registro.tienda.pendiente')
        : redirect()->route('registro.tienda');

})->middleware(['auth', 'signed'])->name('verification.verify');

// Reenviar correo (compartido por todos los roles)
Route::post('/email/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Correo de verificación reenviado.');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
