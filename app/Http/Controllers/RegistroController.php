<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class RegistroController extends Controller
{
    public function create()
    {
        return view('filament.store.pages.auth.registro');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],

            'per_nombre' => ['required', 'string', 'max:255'],
            'per_paterno' => ['required', 'string', 'max:255'],
            'per_materno' => ['nullable', 'string', 'max:255'],
            'per_telefono' => ['required', 'string', 'max:30'],
        ]);

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'], // tu mutator ya lo encripta
            ]);

            Persona::create([
                'per_nombre' => $data['per_nombre'],
                'per_paterno' => $data['per_paterno'],
                'per_materno' => $data['per_materno'] ?? null,
                'per_telefono' => $data['per_telefono'],
                'per_fecha_registro' => now(),
                'user_id' => $user->id,
            ]);
        });

        Auth::attempt([
            'email'    => $data['email'],
            'password' => $data['password'],
        ]);

        Auth::user()->sendEmailVerificationNotification();

        return redirect()->route('tienda.verification.notice');
    }
}
