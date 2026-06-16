<?php

namespace App\Http\Controllers;

use App\Models\EstadoPedido;
use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Webhook;

class StripeController extends Controller
{
    // ── CREAR PAYMENT INTENT ──────────────────────────────
    // Lo llama el checkout cuando elige pago con tarjeta
    public function crearIntent(Request $request)
    {
        $pedidoId = $request->pedido_id;
        $tipoEntrega = $request->tipo_entrega ?? 'domicilio';

        $clienteId = Auth::user()?->cliente?->cli_id;
        abort_unless($clienteId, 403);

        $pedido = Pedido::with('tienda')
            ->where('ped_id', $pedidoId)
            ->where('ped_fk_cliente', $clienteId)
            ->where('ped_estado', 'carrito')
            ->firstOrFail();

        // Calcular total con envío si es domicilio
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

        $intent = PaymentIntent::create([
            'amount'   => (int) round($totalConEnvio * 100), //  total real
            'currency' => 'mxn',
            'metadata' => [
                'pedido_id'   => $pedido->ped_id,
                'user_id'     => Auth::id(),
                'costo_envio' => $costoEnvio,
                'tipo_entrega' => $tipoEntrega,
            ],
        ]);

        return response()->json([
            'client_secret' => $intent->client_secret,
            'intent_id'     => $intent->id,
        ]);
    }

    // ── CONFIRMAR PAGO (después de que Stripe procesa) ────
    public function confirmar(Request $request)
    {
        $request->validate([
            'pedido_id'         => ['required', 'integer'],
            'payment_intent_id' => ['required', 'string'],
            'tipo_entrega'      => ['required', 'in:domicilio,recoger'],
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $intent = PaymentIntent::retrieve($request->payment_intent_id);

        if ($intent->status !== 'succeeded') {
            return back()->withErrors(['pago' => 'El pago no fue completado.']);
        }

        $clienteId = Auth::user()?->cliente?->cli_id;
        abort_unless($clienteId, 403);

        // Verificar que el PaymentIntent fue creado para este usuario/pedido
        if ((int) ($intent->metadata['pedido_id'] ?? 0) !== (int) $request->pedido_id ||
            (int) ($intent->metadata['user_id']   ?? 0) !== Auth::id()) {
            abort(403, 'El pago no corresponde a este pedido.');
        }

        $pedido = Pedido::with('tienda')
            ->where('ped_id', $request->pedido_id)
            ->where('ped_fk_cliente', $clienteId)
            ->firstOrFail();

        //  Calcular costo de envío
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

        //  Actualizar pedido con costo envío y total real
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

        //  Registrar pago con el total real
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

        return redirect()->route('cliente.pedidos')
            ->with('success', 'Pago realizado correctamente.');
    }
    // ── WEBHOOK DE STRIPE ─────────────────────────────────
    // Stripe notifica aquí cuando hay eventos (pago completado, fallido, etc.)
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

        // Manejar el evento
        if ($event->type === 'payment_intent.succeeded') {
            $intent   = $event->data->object;
            $pedidoId = $intent->metadata->pedido_id ?? null;

            if ($pedidoId) {
                $pago = Pago::where('pag_fk_pedido', $pedidoId)->first();
                if ($pago) {
                    $pago->update([
                        'pag_estado'                => 'Aceptado',
                        'pag_stripe_charge_id'      => $intent->latest_charge ?? null,
                    ]);
                }
            }
        }

        if ($event->type === 'payment_intent.payment_failed') {
            $intent   = $event->data->object;
            $pedidoId = $intent->metadata->pedido_id ?? null;

            if ($pedidoId) {
                Pago::where('pag_fk_pedido', $pedidoId)
                    ->update(['pag_estado' => 'Rechazado']);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
