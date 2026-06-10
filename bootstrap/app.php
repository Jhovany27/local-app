<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'stripe/webhook',
        ]);

        // Redirigir al login correcto según la ruta
        $middleware->redirectGuestsTo(function ($request) {
            // Link de verificación de correo: detectar rol por el ID del usuario en la URL
            if ($request->is('email/verify/*')) {
                $segments = explode('/', $request->path());
                $userId   = $segments[2] ?? null;
                $user     = $userId ? \App\Models\User::find($userId) : null;

                if ($user) {
                    if ($user->hasRol('cliente')) {
                        return route('cliente.login');
                    }
                    if ($user->hasRol('repartidor')) {
                        return route('repartidor.login');
                    }
                }
                return route('login');
            }

            if ($request->is('cliente/*') || $request->is('carrito/*')) {
                return route('cliente.login');
            }

            if ($request->is('repartidor/*')) {
                return route('repartidor.login');
            }

            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
