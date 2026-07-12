<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class VerificarCorreoController extends Controller
{
    public function show()
    {
        $userId = session('verificacion_user_id');

        if (!$userId || !($user = User::find($userId))) {
            return redirect()->route('portal');
        }

        if ($user->hasVerifiedEmail()) {
            session()->forget(['verificacion_user_id', 'verificacion_rol']);
            return redirect()->route('portal')->with('success', 'Tu correo ya está verificado. Inicia sesión.');
        }

        return view('auth.verificar-correo', [
            'user' => $user,
            'rol'  => session('verificacion_rol', 'cliente'),
        ]);
    }

    public function reenviar()
    {
        $userId = session('verificacion_user_id');

        if (!$userId || !($user = User::find($userId))) {
            return redirect()->route('portal');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('portal');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', '¡Correo reenviado! Revisa tu bandeja de entrada y también la carpeta de spam.');
    }

    public function actualizarEmail(Request $request)
    {
        $userId = session('verificacion_user_id');

        if (!$userId || !($user = User::find($userId))) {
            return redirect()->route('portal');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('portal');
        }

        $request->validate([
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
        ], [
            'email.required' => 'El correo es obligatorio.',
            'email.email'    => 'Ingresa un correo electrónico válido.',
            'email.unique'   => 'Ese correo ya está registrado con otra cuenta.',
        ]);

        $user->update([
            'email'             => $request->email,
            'email_verified_at' => null,
        ]);

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Correo actualizado. Te enviamos un nuevo enlace de verificación.');
    }
}
