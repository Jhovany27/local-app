<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Account;
use Stripe\AccountLink;

class StripeConnectController extends Controller
{
    private function setKey(): void
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Ejecuta cualquier llamada al SDK Stripe v1 suprimiendo el E_USER_NOTICE
     * que stripe-php v20 lanza recomendando Accounts v2.
     * Laravel convierte E_USER_NOTICE en ErrorException, por eso lo suprimimos.
     */
    private function stripe(callable $fn): mixed
    {
        $prev = set_error_handler(fn() => true, E_USER_NOTICE);
        try {
            return $fn();
        } finally {
            set_error_handler($prev);
        }
    }

    // ══════════════════════════════════════════════════════
    //  TIENDA
    // ══════════════════════════════════════════════════════

    public function onboardingTienda()
    {
        $this->setKey();

        /** @var \App\Models\User $user */
        $user     = Auth::user();
        $tiendaId = (int) session('store_tienda_id');
        $tienda   = $user->tiendas()->where('tie_id', $tiendaId)->firstOrFail();

        if (! $tienda->stripe_account_id) {
            $account = $this->stripe(fn() => Account::create([
                'type'          => 'express',
                'country'       => 'MX',
                'email'         => $user->email,
                'capabilities'  => ['transfers' => ['requested' => true]],
                'business_type' => 'individual',
                'metadata'      => ['tienda_id' => $tienda->tie_id],
            ]));
            $tienda->update(['stripe_account_id' => $account->id]);
        }

        $link = $this->stripe(fn() => AccountLink::create([
            'account'     => $tienda->stripe_account_id,
            'refresh_url' => route('store.stripe.refresh'),
            'return_url'  => route('store.stripe.return'),
            'type'        => 'account_onboarding',
        ]));

        return redirect($link->url);
    }

    public function returnTienda()
    {
        $this->setKey();

        /** @var \App\Models\User $user */
        $user     = Auth::user();
        $tiendaId = (int) session('store_tienda_id');
        $tienda   = $user->tiendas()->where('tie_id', $tiendaId)->firstOrFail();

        if ($tienda->stripe_account_id) {
            $account = $this->stripe(fn() => Account::retrieve($tienda->stripe_account_id));
            if ($account->payouts_enabled) {
                return redirect()->route('filament.store.pages.mi-tienda')
                    ->with('cuenta_ok', '¡Cuenta bancaria conectada con Stripe! Ya puedes recibir liquidaciones automáticas.');
            }
            // Onboarding no completado: limpiar ID para que la UI no muestre "conectada"
            $tienda->update(['stripe_account_id' => null]);
        }

        return redirect()->route('filament.store.pages.mi-tienda')
            ->with('cuenta_warning', 'El proceso no se completó. Intenta conectar tu cuenta nuevamente.');
    }

    public function refreshTienda()
    {
        $this->setKey();

        /** @var \App\Models\User $user */
        $user     = Auth::user();
        $tiendaId = (int) session('store_tienda_id');
        $tienda   = $user->tiendas()->where('tie_id', $tiendaId)->firstOrFail();
        abort_unless($tienda->stripe_account_id, 400, 'No hay cuenta Connect iniciada.');

        $link = $this->stripe(fn() => AccountLink::create([
            'account'     => $tienda->stripe_account_id,
            'refresh_url' => route('store.stripe.refresh'),
            'return_url'  => route('store.stripe.return'),
            'type'        => 'account_onboarding',
        ]));

        return redirect($link->url);
    }

    // ══════════════════════════════════════════════════════
    //  REPARTIDOR
    // ══════════════════════════════════════════════════════

    public function onboardingRepartidor()
    {
        $this->setKey();

        /** @var \App\Models\User $user */
        $user       = Auth::user();
        $repartidor = $user->repartidors()->firstOrFail();

        if (! $repartidor->stripe_account_id) {
            $account = $this->stripe(fn() => Account::create([
                'type'          => 'express',
                'country'       => 'MX',
                'email'         => $user->email,
                'capabilities'  => ['transfers' => ['requested' => true]],
                'business_type' => 'individual',
                'metadata'      => ['repartidor_id' => $repartidor->rep_id],
            ]));
            $repartidor->update(['stripe_account_id' => $account->id]);
        }

        $link = $this->stripe(fn() => AccountLink::create([
            'account'     => $repartidor->stripe_account_id,
            'refresh_url' => route('repartidor.stripe.refresh'),
            'return_url'  => route('repartidor.stripe.return'),
            'type'        => 'account_onboarding',
        ]));

        return redirect($link->url);
    }

    public function returnRepartidor()
    {
        $this->setKey();

        /** @var \App\Models\User $user */
        $user       = Auth::user();
        $repartidor = $user->repartidors()->firstOrFail();

        if ($repartidor->stripe_account_id) {
            $account = $this->stripe(fn() => Account::retrieve($repartidor->stripe_account_id));
            if ($account->payouts_enabled) {
                return redirect()->route('repartidor.cuenta')
                    ->with('cuenta_ok', '¡Cuenta bancaria conectada! Ya puedes recibir liquidaciones automáticas.');
            }
            // Onboarding no completado: limpiar ID para que la UI no muestre "conectada"
            $repartidor->update(['stripe_account_id' => null]);
        }

        return redirect()->route('repartidor.cuenta')
            ->with('cuenta_warning', 'El proceso no se completó. Intenta conectar tu cuenta nuevamente.');
    }

    public function refreshRepartidor()
    {
        $this->setKey();

        /** @var \App\Models\User $user */
        $user       = Auth::user();
        $repartidor = $user->repartidors()->firstOrFail();
        abort_unless($repartidor->stripe_account_id, 400);

        $link = $this->stripe(fn() => AccountLink::create([
            'account'     => $repartidor->stripe_account_id,
            'refresh_url' => route('repartidor.stripe.refresh'),
            'return_url'  => route('repartidor.stripe.return'),
            'type'        => 'account_onboarding',
        ]));

        return redirect($link->url);
    }

    // ══════════════════════════════════════════════════════
    //  HELPER ESTÁTICO — ejecutar transferencia
    // ══════════════════════════════════════════════════════

    public static function transferir(string $stripeAccountId, float $monto, string $descripcion, array $metadata = []): string
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $prev = set_error_handler(fn() => true, E_USER_NOTICE);
        try {
            $transfer = \Stripe\Transfer::create([
                'amount'      => (int) round($monto * 100),
                'currency'    => 'mxn',
                'destination' => $stripeAccountId,
                'description' => $descripcion,
                'metadata'    => $metadata,
            ]);
        } finally {
            set_error_handler($prev);
        }

        return $transfer->id;
    }
}
