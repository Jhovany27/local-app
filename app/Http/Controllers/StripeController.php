<?php

namespace App\Http\Controllers;

use App\Models\EstadoPedido;
use App\Models\Pago;
use App\Models\Pedido;
use App\Models\TarjetaCliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeController extends Controller
{
    // ── CREAR PAYMENT INTENT ──────────────────────────────
    public function crearIntent(Request $request)
    {
        $pedidoId    = $request->pedido_id;
        $tipoEntrega = $request->tipo_entrega ?? 'domicilio';

        $clienteId = Auth::user()?->cliente?->cli_id;
        abort_unless($clienteId, 403);

        $pedido = Pedido::with('tienda')
            ->where('ped_id', $pedidoId)
            ->where('ped_fk_cliente', $clienteId)
            ->where('ped_estado', 'carrito')
            ->firstOrFail();

        $subtotal   = $pedido->detalles()->sum('det_subtotal');
        $costoEnvio = 0;

        if ($tipoEntrega === 'domicilio') {
            $direccion = session('direccion_id')
                ? \App\Models\Direccion::where('drc_id', session('direccion_id'))
                    ->where('user_id', Auth::id())
                    ->first()
                : null;

            if (
                $direccion?->drc_latitud && $direccion?->drc_longitud &&
                $pedido->tienda->tie_latitud && $pedido->tienda->tie_longitud
            ) {
                $envio      = \App\Services\EnvioCalculator::calcular(
                    (float) $pedido->tienda->tie_latitud,
                    (float) $pedido->tienda->tie_longitud,
                    (float) $direccion->drc_latitud,
                    (float) $direccion->drc_longitud,
                );
                $costoEnvio = $envio['costo_envio'];
            }
        }

        $totalConEnvio = $subtotal + $costoEnvio;

        Stripe::setApiKey(config('services.stripe.secret'));

        $intentParams = [
            'amount'   => (int) round($totalConEnvio * 100),
            'currency' => 'mxn',
            'metadata' => [
                'pedido_id'    => $pedido->ped_id,
                'user_id'      => Auth::id(),
                'costo_envio'  => $costoEnvio,
                'tipo_entrega' => $tipoEntrega,
            ],
        ];

        $user = Auth::user();

        // Si el usuario ya tiene customer en Stripe, adjuntarlo siempre
        // (requerido para pagar con tarjetas guardadas que pertenecen al customer)
        if ($user->stripe_customer_id) {
            $intentParams['customer'] = $user->stripe_customer_id;
        } elseif ($request->boolean('guardar_tarjeta')) {
            // Crear customer solo si el usuario quiere guardar la tarjeta
            $intentParams['customer'] = TarjetaController::obtenerOCrearCustomer($user);
        }

        if ($request->boolean('guardar_tarjeta')) {
            $intentParams['setup_future_usage'] = 'off_session';
        }

        $intent = PaymentIntent::create($intentParams);

        return response()->json([
            'client_secret' => $intent->client_secret,
            'intent_id'     => $intent->id,
        ]);
    }

    // ── CONFIRMAR PAGO (después de que Stripe procesa) ────
    public function confirmar(Request $request)
    {
        $request->validate([
            'pedido_id'          => ['required', 'integer'],
            'payment_intent_id'  => ['required', 'string'],
            'tipo_entrega'       => ['required', 'in:domicilio,recoger'],
            'payment_method_id'  => ['nullable', 'string'],
            'guardar_tarjeta'    => ['nullable'],
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $intent = PaymentIntent::retrieve($request->payment_intent_id);

        if ($intent->status !== 'succeeded') {
            return back()->withErrors(['pago' => 'El pago no fue completado.']);
        }

        $clienteId = Auth::user()?->cliente?->cli_id;
        abort_unless($clienteId, 403);

        if ((int) ($intent->metadata['pedido_id'] ?? 0) !== (int) $request->pedido_id ||
            (int) ($intent->metadata['user_id']   ?? 0) !== Auth::id()) {
            abort(403, 'El pago no corresponde a este pedido.');
        }

        $pedido = Pedido::with('tienda')
            ->where('ped_id', $request->pedido_id)
            ->where('ped_fk_cliente', $clienteId)
            ->firstOrFail();

        $subtotal   = $pedido->detalles()->sum('det_subtotal');
        $costoEnvio = 0;

        if ($request->tipo_entrega === 'domicilio') {
            $direccion = session('direccion_id')
                ? \App\Models\Direccion::where('drc_id', session('direccion_id'))
                    ->where('user_id', Auth::id())
                    ->first()
                : null;

            if (
                $direccion?->drc_latitud && $direccion?->drc_longitud &&
                $pedido->tienda->tie_latitud && $pedido->tienda->tie_longitud
            ) {
                $envio      = \App\Services\EnvioCalculator::calcular(
                    (float) $pedido->tienda->tie_latitud,
                    (float) $pedido->tienda->tie_longitud,
                    (float) $direccion->drc_latitud,
                    (float) $direccion->drc_longitud,
                );
                $costoEnvio = $envio['costo_envio'];
            }
        }

        $totalFinal = $subtotal + $costoEnvio;

        $pedido->update([
            'ped_estado'       => 'pendiente',
            'ped_tipo_entrega' => strtolower($request->tipo_entrega),
            'ped_codigo'       => 'PED-' . strtoupper(\Illuminate\Support\Str::random(8)),
            'ped_fecha_pedido' => now(),
            'ped_costo_envio'  => $costoEnvio,
            'ped_total'        => $totalFinal,
            'ped_fk_direccion' => $request->tipo_entrega === 'domicilio'
                ? session('direccion_id')
                : null,
        ]);

        Pago::updateOrCreate(
            ['pag_fk_pedido' => $pedido->ped_id],
            [
                'pag_monto'                 => $totalFinal,
                'pag_estado'                => 'Aceptado',
                'pag_metodo_pago'           => 'Tarjeta',
                'pag_stripe_payment_intent' => $intent->id,
                'pag_stripe_charge_id'      => $intent->latest_charge ?? null,
                'pag_fecha'                 => now(),
            ]
        );

        EstadoPedido::create([
            'esp_nombre'       => 'pendiente',
            'esp_fecha_cambio' => now(),
            'esp_fk_pedido'    => $pedido->ped_id,
        ]);

        // Guardar tarjeta si el usuario lo solicitó
        if ($request->filled('payment_method_id') && $request->input('guardar_tarjeta') === '1') {
            $this->guardarMetodoPago($request->payment_method_id);
        }

        \App\Services\TiendaNotificacion::enviar(
            $pedido->ped_fk_tienda,
            'Nuevo pedido recibido',
            "#{$pedido->ped_codigo} — $" . number_format($totalFinal, 2) . " (Tarjeta)",
            'info',
            'heroicon-o-shopping-bag'
        );

        return redirect()->route('cliente.pedidos')
            ->with('success', 'Pago realizado correctamente.');
    }

    // ── WEBHOOK DE STRIPE ─────────────────────────────────
    public function webhook(Request $request)
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        match ($event->type) {
            'payment_intent.succeeded'      => $this->handlePaymentSucceeded($event->data->object),
            'payment_intent.payment_failed' => $this->handlePaymentFailed($event->data->object),
            'charge.refunded'               => $this->handleChargeRefunded($event->data->object),
            'payment_method.detached'       => $this->handlePaymentMethodDetached($event->data->object),
            default                         => null,
        };

        return response()->json(['status' => 'ok']);
    }

    // Backup: confirmar pago si el cliente cerró la app antes de que nuestro backend respondiera
    private function handlePaymentSucceeded(object $intent): void
    {
        $pedidoId = $intent->metadata->pedido_id ?? null;
        if (! $pedidoId) return;

        $pago = Pago::where('pag_fk_pedido', $pedidoId)->first();
        if (! $pago) return;

        if ($pago->pag_estado !== 'Aceptado') {
            $pago->update([
                'pag_estado'           => 'Aceptado',
                'pag_stripe_charge_id' => $intent->latest_charge ?? null,
            ]);
        }

        // Si el pedido aún está en 'carrito', pasarlo a 'pendiente' como fallback
        $pedido = Pedido::where('ped_id', $pedidoId)
            ->where('ped_estado', 'carrito')
            ->first();

        if ($pedido) {
            $tipoEntrega = strtolower($intent->metadata->tipo_entrega ?? 'domicilio');
            $costoEnvio  = (float) ($intent->metadata->costo_envio ?? 0);

            $pedido->update([
                'ped_estado'       => 'pendiente',
                'ped_tipo_entrega' => $tipoEntrega,
                'ped_codigo'       => 'PED-' . strtoupper(\Illuminate\Support\Str::random(8)),
                'ped_fecha_pedido' => now(),
                'ped_costo_envio'  => $costoEnvio,
                'ped_total'        => $pedido->detalles()->sum('det_subtotal') + $costoEnvio,
            ]);

            EstadoPedido::create([
                'esp_nombre'       => 'pendiente',
                'esp_fecha_cambio' => now(),
                'esp_fk_pedido'    => $pedido->ped_id,
            ]);
        }
    }

    // Pago fallido → marcar en DB
    private function handlePaymentFailed(object $intent): void
    {
        $pedidoId = $intent->metadata->pedido_id ?? null;
        if (! $pedidoId) return;

        Pago::where('pag_fk_pedido', $pedidoId)
            ->where('pag_estado', '!=', 'Aceptado')
            ->update(['pag_estado' => 'Rechazado']);
    }

    // Reembolso iniciado desde el dashboard de Stripe → sincronizar con nuestra DB
    private function handleChargeRefunded(object $charge): void
    {
        if (! $charge->refunded) return;

        $pago = Pago::where('pag_stripe_charge_id', $charge->id)->first();
        if (! $pago) return;

        $pago->update(['pag_estado' => 'Reembolsado']);

        // Cancelar el pedido si aún está activo
        $pedido = Pedido::where('ped_id', $pago->pag_fk_pedido)
            ->whereIn('ped_estado', ['pendiente', 'en_preparacion'])
            ->first();

        if ($pedido) {
            $pedido->update([
                'ped_estado'             => 'cancelado',
                'ped_motivo_cancelacion' => 'Reembolso procesado por Stripe.',
                'ped_cancelado_por'      => 'sistema',
            ]);

            EstadoPedido::create([
                'esp_nombre'       => 'cancelado',
                'esp_fecha_cambio' => now(),
                'esp_fk_pedido'    => $pedido->ped_id,
            ]);
        }
    }

    // Tarjeta eliminada desde el dashboard de Stripe → limpiar de nuestra DB
    private function handlePaymentMethodDetached(object $pm): void
    {
        TarjetaCliente::where('tar_stripe_pm_id', $pm->id)->delete();
    }

    // ── GUARDAR MÉTODO DE PAGO TRAS CHECKOUT ─────────────
    private function guardarMetodoPago(string $pmId): void
    {
        try {
            $user = Auth::user();

            if (TarjetaCliente::where('tar_stripe_pm_id', $pmId)->where('tar_fk_user', $user->id)->exists()) {
                return;
            }

            $pm         = PaymentMethod::retrieve($pmId);
            $customerId = TarjetaController::obtenerOCrearCustomer($user);

            if ($pm->customer !== $customerId) {
                $pm->attach(['customer' => $customerId]);
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
        } catch (\Exception) {
            // No crítico — no fallar el pago por esto
        }
    }
}
