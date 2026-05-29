<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class ClienteAuthController extends Controller
{
    // ── LOGIN ────────────────────────────────────────────

    public function showLogin()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // Si ya está logueado con rol cliente → ir directo
        if (Auth::check() && $user->hasRol('cliente')) {
            return redirect()->route('cliente.index');
        }

        return view('cliente.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            if (!$user->hasRol('cliente')) {
                $user->roles()->attach(2);
            }

            if (!$user->cliente) {
                \App\Models\Cliente::create(['user_id' => $user->id]);
            }

            // ✅ Migrar carrito de sesión a BD al iniciar sesión
            \App\Http\Controllers\CarritoController::migrarSesionABD();

            $redirect = $request->query('redirect');
            return match ($redirect) {
                'carrito'     => redirect()->route('carrito.index'),
                'pedido'      => redirect()->route('cliente.pedido'),
                'direcciones' => redirect()->route('cliente.direcciones'),
                default       => redirect()->route('cliente.index'),
            };
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas.'])->onlyInput('email');
    }

    // ── REGISTRO ─────────────────────────────────────────

    public function showRegistro()
    {
        return view('cliente.auth.registro');
    }

    public function registro(Request $request)
    {
        $data = $request->validate([
            'per_nombre'   => ['required', 'string', 'max:255'],
            'per_paterno'  => ['required', 'string', 'max:255'],
            'per_materno'  => ['nullable', 'string', 'max:255'],
            'per_telefono' => ['required', 'string', 'max:30'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'     => ['required', 'confirmed', Password::min(8)],
            'privacidad'   => ['accepted'],
        ]);

        DB::transaction(function () use ($data) {

            // Crear usuario
            $user = User::create([
                'name'     => $data['per_nombre'] . ' ' . $data['per_paterno'],
                'email'    => $data['email'],
                'password' => $data['password'],
            ]);

            // Crear persona
            Persona::create([
                'per_nombre'         => $data['per_nombre'],
                'per_paterno'        => $data['per_paterno'],
                'per_materno'        => $data['per_materno'],
                'per_telefono'       => $data['per_telefono'],
                'per_fecha_registro' => now(),
                'user_id'            => $user->id,
            ]);

            // Crear cliente
            Cliente::create(['user_id' => $user->id]);

            //  Asignar rol cliente automáticamente (rol_id = 2)
            $user->roles()->attach(2);
        });

        // Auto login tras registro
        Auth::attempt([
            'email'    => $data['email'],
            'password' => $data['password'],
        ]);

        return redirect()->route('cliente.index');
    }

    public function perfil()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $persona = $user->persona;

        return view('cliente.perfil', compact('user', 'persona'));
    }

    // ── LOGOUT ───────────────────────────────────────────


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('cliente.index');
    }
}
