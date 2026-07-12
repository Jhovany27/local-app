<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('filament.store.pages.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                session(['verificacion_user_id' => $user->id, 'verificacion_rol' => 'tienda']);
                return back()->withErrors(['email' => 'verificar_correo'])->onlyInput('email');
            }

            //  Si tiene rol tienda → entrar al panel
            if ($user->hasRol('tienda')) {
                return redirect('/store');
            }

            //  Si tiene tienda pendiente → pantalla de espera
            if ($user->tiendaPendiente()) {
                return redirect()->route('registro.tienda.pendiente');
            }

            //  Si tiene tienda rechazada → pantalla de espera
            if ($user->tiendaRechazada()) {
                return redirect()->route('registro.tienda.pendiente');
            }

            // Sin tienda → registrar tienda
            if (!$user->tiendas()->exists()) {
                return redirect()->route('registro.tienda');
            }

            // Cualquier otro caso → portal
            return redirect()->route('portal');
        }

        return back()->withErrors([
            'email' => 'Credenciales incorrectas',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/store/login');
    }
}
