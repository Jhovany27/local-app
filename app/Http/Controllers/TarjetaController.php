<?php

namespace App\Http\Controllers;

use App\Models\TarjetaCliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Customer;
use Stripe\PaymentMethod;
use Stripe\SetupIntent;
use Stripe\Stripe;

class TarjetaController extends Controller
{
    public function index()
    {
        $tarjetas = TarjetaCliente::where('tar_fk_user', Auth::id())
            ->orderByDesc('tar_es_default')
            ->orderByDesc('created_at')
            ->get();

        return view('cliente.tarjetas.index', compact('tarjetas'));
    }

    public function agregar()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $customerId = self::obtenerOCrearCustomer(Auth::user());

        $setupIntent = SetupIntent::create([
            'customer' => $customerId,
            'usage'    => 'off_session',
        ]);

        return view('cliente.tarjetas.agregar', [
            'clientSecret' => $setupIntent->client_secret,
        ]);
    }

    public function guardar(Request $request)
    {
        $request->validate(['payment_method_id' => 'required|string']);

        $user = Auth::user();
        Stripe::setApiKey(config('services.stripe.secret'));

        $customerId = self::obtenerOCrearCustomer($user);
        $pm         = PaymentMethod::retrieve($request->payment_method_id);

        if ($pm->customer !== $customerId) {
            $pm->attach(['customer' => $customerId]);
        }

        if (TarjetaCliente::where('tar_stripe_pm_id', $pm->id)->where('tar_fk_user', $user->id)->exists()) {
            return redirect()->route('cliente.tarjetas')
                ->with('info', 'Esta tarjeta ya está guardada.');
        }

        $esDefault = TarjetaCliente::where('tar_fk_user', $user->id)->count() === 0;

        TarjetaCliente::create([
            'tar_fk_user'      => $user->id,
            'tar_stripe_pm_id' => $pm->id,
            'tar_brand'        => $pm->card->brand,
            'tar_last4'        => $pm->card->last4,
            'tar_exp_month'    => $pm->card->exp_month,
            'tar_exp_year'     => $pm->card->exp_year,
            'tar_es_default'   => $esDefault,
        ]);

        return redirect()->route('cliente.tarjetas')
            ->with('success', '¡Tarjeta guardada exitosamente!');
    }

    public function eliminar(int $id)
    {
        $user    = Auth::user();
        $tarjeta = TarjetaCliente::where('tar_id', $id)
            ->where('tar_fk_user', $user->id)
            ->firstOrFail();

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            PaymentMethod::retrieve($tarjeta->tar_stripe_pm_id)->detach();
        } catch (\Exception) {
            // Already removed from Stripe
        }

        $wasDefault = $tarjeta->tar_es_default;
        $tarjeta->delete();

        if ($wasDefault) {
            TarjetaCliente::where('tar_fk_user', $user->id)
                ->orderByDesc('created_at')
                ->first()
                ?->update(['tar_es_default' => true]);
        }

        return back()->with('success', 'Tarjeta eliminada.');
    }

    public function predeterminar(int $id)
    {
        $user    = Auth::user();
        $tarjeta = TarjetaCliente::where('tar_id', $id)
            ->where('tar_fk_user', $user->id)
            ->firstOrFail();

        TarjetaCliente::where('tar_fk_user', $user->id)->update(['tar_es_default' => false]);
        $tarjeta->update(['tar_es_default' => true]);

        return back()->with('success', 'Tarjeta predeterminada actualizada.');
    }

    public static function obtenerOCrearCustomer(User $user): string
    {
        if ($user->stripe_customer_id) {
            return $user->stripe_customer_id;
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $customer = Customer::create([
            'email'    => $user->email,
            'name'     => $user->name,
            'metadata' => ['user_id' => $user->id],
        ]);

        $user->update(['stripe_customer_id' => $customer->id]);

        return $customer->id;
    }
}
