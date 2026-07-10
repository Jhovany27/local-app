<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
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

            //  Verificar si el correo está confirmado
            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                return back()->withErrors(['email' => 'Debes verificar tu correo antes de iniciar sesión.'])->onlyInput('email');
            }

            if (!$user->hasRol('cliente')) {
                $user->roles()->attach(2);
            }

            if (!$user->cliente) {
                \App\Models\Cliente::create(['user_id' => $user->id]);
            }

            //  Migrar carrito de sesión a BD al iniciar sesión
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
            'password'     => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'privacidad'   => ['accepted'],
        ]);

        DB::transaction(function () use ($data) {

            $user = User::create([
                'name'     => $data['per_nombre'] . ' ' . $data['per_paterno'],
                'email'    => $data['email'],
                'password' => $data['password'],
            ]);

            Persona::create([
                'per_nombre'         => $data['per_nombre'],
                'per_paterno'        => $data['per_paterno'],
                'per_materno'        => $data['per_materno'],
                'per_telefono'       => $data['per_telefono'],
                'per_fecha_registro' => now(),
                'user_id'            => $user->id,
            ]);

            Cliente::create(['user_id' => $user->id]);

            $user->roles()->attach(2);
        });

        // Auto login tras registro
        Auth::attempt([
            'email'    => $data['email'],
            'password' => $data['password'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Enviar correo de verificación y redirigir
        $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice');
    }

    public function perfil()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $persona = $user->persona;

        return view('cliente.perfil', compact('user', 'persona'));
    }

    public function editarPerfil()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $persona = $user->persona;

        return view('cliente.perfil-editar', compact('user', 'persona'));
    }

    public function actualizarPerfil(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $data = $request->validate([
            'per_nombre'      => ['required', 'string', 'max:255'],
            'per_paterno'     => ['required', 'string', 'max:255'],
            'per_materno'     => ['nullable', 'string', 'max:255'],
            'per_telefono'    => ['required', 'string', 'max:30'],
            'email'           => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password_actual' => ['nullable', 'required_with:nueva_password', 'string'],
            'nueva_password'  => ['nullable', 'string', Password::min(8)->mixedCase()->numbers(), 'confirmed'],
        ]);

        // Validar contraseña actual si se quiere cambiar
        if ($request->filled('nueva_password')) {
            if (!Hash::check($data['password_actual'] ?? '', $user->password)) {
                return back()
                    ->withErrors(['password_actual' => 'La contraseña actual es incorrecta.'])
                    ->withInput();
            }
        }

        $emailCambio = $data['email'] !== $user->email;

        // Actualizar Persona
        $persona = $user->persona;
        $personaData = [
            'per_nombre'   => $data['per_nombre'],
            'per_paterno'  => $data['per_paterno'],
            'per_materno'  => $data['per_materno'],
            'per_telefono' => $data['per_telefono'],
        ];
        if ($persona) {
            $persona->update($personaData);
        } else {
            Persona::create(array_merge($personaData, [
                'per_fecha_registro' => now(),
                'user_id'            => $user->id,
            ]));
        }

        // Actualizar User
        $user->name = $data['per_nombre'] . ' ' . $data['per_paterno'];

        if ($emailCambio) {
            $user->email = $data['email'];
            $user->email_verified_at = null;
        }

        if ($request->filled('nueva_password')) {
            $user->password = $data['nueva_password'];
        }

        $user->save();

        if ($emailCambio) {
            $user->sendEmailVerificationNotification();
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('verification.notice')
                ->with('status', 'Hemos enviado un enlace de verificación a ' . $data['email'] . '. Por favor verifica tu nuevo correo para continuar.');
        }

        return redirect()->route('cliente.perfil')
            ->with('success', 'Perfil actualizado correctamente.');
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
