<?php

namespace App\Http\Controllers;

use App\Models\DocumentoRepartidor;
use App\Models\Persona;
use App\Models\Repartidor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class RepartidorAuthController extends Controller
{
    // ── LOGIN ─────────────────────────────────────────────
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirigirSegunEstado(Auth::user());
        }
        return view('repartidor.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials, $request->remember)) {
            return back()->withErrors(['email' => 'Credenciales incorrectas.'])->onlyInput('email');
        }

        $request->session()->regenerate();
        return $this->redirigirSegunEstado(Auth::user());
    }

    private function redirigirSegunEstado(\App\Models\User $user)
    {
        // Si no tiene rol repartidor → solicitar datos de repartidor
        if (!$user->hasRol('repartidor')) {
            return redirect()->route('repartidor.completar-perfil');
        }

        $repartidor = $user->repartidors()->first();

        if (!$repartidor || (int)$repartidor->rep_estado === 0) {
            return redirect()->route('repartidor.pendiente');
        }

        return redirect()->route('repartidor.index');
    }

    // ── REGISTRO (cuenta nueva) ───────────────────────────
    public function showRegistro()
    {
        return view('repartidor.auth.registro');
    }

    public function registro(Request $request)
    {
        $data = $request->validate([
            'per_nombre'        => ['required', 'string', 'max:255'],
            'per_paterno'       => ['required', 'string', 'max:255'],
            'per_telefono'      => ['required', 'string', 'max:30'],
            'rep_tipo_vehiculo' => ['required', 'in:Motocicleta,Automovil,Bicicleta,Pie'],
            'email'             => ['required', 'email', 'unique:users,email'],
            'password'          => ['required', 'confirmed', Password::min(8)],
            'ine'               => ['required', 'file', 'mimes:pdf', 'max:4096'],
            'licencia'          => ['required', 'file', 'mimes:pdf', 'max:4096'],
            'circulacion'       => ['required', 'file', 'mimes:pdf', 'max:4096'],
            'foto_perfil'       => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        DB::transaction(function () use ($data, $request) {
            $user = User::create([
                'name'     => $data['per_nombre'] . ' ' . $data['per_paterno'],
                'email'    => $data['email'],
                'password' => $data['password'],
            ]);

            // Crear persona solo si no existe
            if (!$user->persona) {
                Persona::create([
                    'per_nombre'         => $data['per_nombre'],
                    'per_paterno'        => $data['per_paterno'],
                    'per_materno'        => '',
                    'per_telefono'       => $data['per_telefono'],
                    'per_fecha_registro' => now(),
                    'user_id'            => $user->id,
                ]);
            }

            $repartidor = Repartidor::create([
                'user_id'           => $user->id,
                'rep_tipo_vehiculo' => $data['rep_tipo_vehiculo'],
                'rep_estado'        => 0,
            ]);

            $user->roles()->attach(3);
            $this->guardarDocumentos($repartidor->rep_id, $request);
        });

        Auth::attempt(['email' => $data['email'], 'password' => $data['password']]);
        return redirect()->route('repartidor.pendiente');
    }

    // ── COMPLETAR PERFIL (usuario ya logueado sin rol repartidor) ──
    public function showCompletarPerfil()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        abort_unless($user, 403);

        // Si ya tiene rol repartidor → redirigir
        if ($user->hasRol('repartidor')) {
            return $this->redirigirSegunEstado($user);
        }

        return view('repartidor.auth.completar-perfil', compact('user'));
    }

    public function completarPerfil(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        abort_unless($user, 403);

        $data = $request->validate([
            'rep_tipo_vehiculo' => ['required', 'in:Motocicleta,Automovil,Bicicleta,Pie'],
            'per_telefono'      => ['nullable', 'string', 'max:30'],
            'ine'               => ['required', 'file', 'mimes:pdf', 'max:4096'],
            'licencia'          => ['required', 'file', 'mimes:pdf', 'max:4096'],
            'circulacion'       => ['required', 'file', 'mimes:pdf', 'max:4096'],
            'foto_perfil'       => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        DB::transaction(function () use ($user, $data, $request) {
            // Actualizar teléfono si lo ingresó y no tenía
            $telefono = $data['per_telefono'] ?? null;
            if ($telefono && $user->persona) {
                $user->persona->update(['per_telefono' => $telefono]);
            }
            $repartidor = Repartidor::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'rep_tipo_vehiculo' => $data['rep_tipo_vehiculo'],
                    'rep_estado'        => 0,
                ]
            );

            $repartidor->update([
                'rep_tipo_vehiculo' => $data['rep_tipo_vehiculo'],
                'rep_estado'        => 0,
            ]);

            // Asignar rol repartidor si no lo tiene
            if (!$user->hasRol('repartidor')) {
                $user->roles()->attach(3);
            }

            $this->guardarDocumentos($repartidor->rep_id, $request);
        });

        return redirect()->route('repartidor.pendiente');
    }

    // ── HELPER: guardar documentos ────────────────────────
    private function guardarDocumentos(int $repartidorId, Request $request): void
    {
        $docs = [
            1 => $request->file('ine'),
            2 => $request->file('licencia'),
            3 => $request->file('circulacion'),
            4 => $request->file('foto_perfil'),
        ];

        foreach ($docs as $tipoId => $file) {
            if ($file) {
                $carpeta = $tipoId === 4 ? 'fotos_repartidor' : 'docs_repartidor';
                DocumentoRepartidor::updateOrCreate(
                    [
                        'dor_fk_repartidor'     => $repartidorId,
                        'dor_fk_tipo_documento' => $tipoId,
                    ],
                    [
                        'dor_ruta'  => $file->store($carpeta, 'public'),
                        'dor_fecha' => now(),
                    ]
                );
            }
        }
    }

    // ── PENDIENTE ─────────────────────────────────────────
    public function pendiente()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        abort_unless($user, 403);

        $repartidor = $user->repartidors()->first();
        if ($repartidor && (int)$repartidor->rep_estado === 1) {
            return redirect()->route('repartidor.index');
        }

        return view('repartidor.auth.pendiente');
    }

    // ── LOGOUT ────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('repartidor.login');
    }
}
