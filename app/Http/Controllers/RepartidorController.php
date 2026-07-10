<?php

namespace App\Http\Controllers;

use App\Events\PedidoActualizado;
use App\Models\AsignacionRepartidor;
use App\Models\DisponibilidadRepar;
use App\Models\EstadoPedido;
use App\Models\Pedido;
use App\Models\Repartidor;
use App\Services\DistribucionPagoService;
use App\Services\RepartidorDeudaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RepartidorController extends Controller
{
    private function getRepartidor(): Repartidor
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $repartidor = $user->repartidors()->first();
        abort_unless($repartidor, 403);
        return $repartidor;
    }

    // ── INDEX ─────────────────────────────────────────────
    public function index()
    {
        $repartidor = $this->getRepartidor();

        $pedidos = Pedido::with(['tienda', 'detalles', 'pago'])
            ->where('ped_estado', 'listo')
            ->where('ped_tipo_entrega', 'domicilio')
            ->whereDoesntHave('asignacion', fn($q) => $q->whereIn('asr_estado', [0, 1, 2]))
            ->when($repartidor->rep_ciudad, function ($q) use ($repartidor) {
                $q->whereHas('direccion', fn($d) => $d->where('drc_ciudad', 'LIKE', '%' . $repartidor->rep_ciudad . '%'));
            })
            ->latest('ped_fecha_pedido')
            ->get();

        $asignacionActiva = AsignacionRepartidor::with(['pedido.tienda'])
            ->where('asr_fk_repartidor', $repartidor->rep_id)
            ->whereIn('asr_estado', [0, 1, 2])
            ->latest('asr_fecha')
            ->first();

        $pedidoActivo   = $asignacionActiva?->pedido;
        $disponibilidad = DisponibilidadRepar::where('dir_fk_repartidor', $repartidor->rep_id)->first();

        // Pedidos en efectivo pendientes de liquidar con la tienda
        $pedidoPendienteLiq = Pedido::whereHas('asignacion', fn($q) => $q->where('asr_fk_repartidor', $repartidor->rep_id))
            ->where('ped_estado_liquidacion', Pedido::LIQ_PENDIENTE)
            ->latest('ped_fecha_pedido')
            ->first();

        $deudaInfo = RepartidorDeudaService::resumen($repartidor);

        return view('repartidor.index', compact('pedidos', 'pedidoActivo', 'asignacionActiva', 'repartidor', 'disponibilidad', 'pedidoPendienteLiq', 'deudaInfo'));
    }

    // ── DETALLE PEDIDO ────────────────────────────────────
    public function show(int $pedidoId)
    {
        $repartidor = $this->getRepartidor();

        $pedido = Pedido::with([
            'tienda',
            'detalles.producto',
            'cliente.user.persona',
            'cliente.user.direccions',
            'direccion',
            'pago',
        ])
            ->where('ped_id', $pedidoId)
            ->where('ped_estado', 'listo')
            ->where('ped_tipo_entrega', 'domicilio')
            ->where(function ($q) use ($repartidor) {
                $q->whereDoesntHave('asignacion')
                  ->orWhereHas('asignacion', fn($q2) => $q2->where('asr_fk_repartidor', $repartidor->rep_id));
            })
            ->firstOrFail();

        $direccion   = $pedido->direccion
            ?? $pedido->cliente?->user?->direccions()->latest('drc_id')->first();

        $esEfectivo  = strtolower($pedido->pago?->pag_metodo_pago ?? '') === 'efectivo';
        $montoParaTienda = null;
        if ($esEfectivo) {
            $subtotal        = max(0, $pedido->ped_total - ($pedido->ped_costo_envio ?? 0));
            $pctComision     = \App\Models\ConfiguracionComision::porcentajeActual();
            $montoParaTienda = round($subtotal - round($subtotal * $pctComision / 100, 2), 2);
        }

        return view('repartidor.pedido', compact('pedido', 'direccion', 'esEfectivo', 'montoParaTienda'));
    }

    // ── ACEPTAR ───────────────────────────────────────────
    public function aceptar(int $pedidoId)
    {
        $repartidor = $this->getRepartidor();

        $tieneActivo = AsignacionRepartidor::where('asr_fk_repartidor', $repartidor->rep_id)
            ->whereIn('asr_estado', [0, 1, 2])
            ->exists();

        if ($tieneActivo) {
            return back()->with('error', 'Ya tienes un pedido activo.');
        }

        if (RepartidorDeudaService::superaLimite($repartidor)) {
            $paraDesbloqueo = RepartidorDeudaService::montoParaDesbloqueo($repartidor);
            return back()->with('error',
                "Tu saldo pendiente con la plataforma excede el límite permitido. " .
                "Liquida al menos $" . number_format($paraDesbloqueo, 2) . " para continuar recibiendo pedidos."
            );
        }

        $pedido = Pedido::where('ped_id', $pedidoId)
            ->where('ped_estado', 'listo')
            ->firstOrFail();

        AsignacionRepartidor::create([
            'asr_fecha'         => now(),
            'asr_estado'        => 0,
            'asr_fk_repartidor' => $repartidor->rep_id,
            'asr_fk_pedido'     => $pedido->ped_id,
        ]);

        DisponibilidadRepar::updateOrCreate(
            ['dir_fk_repartidor' => $repartidor->rep_id],
            ['dir_estado' => 'ocupado', 'dir_actualizacion' => now()]
        );

        //  Notificar al cliente
        $pedido->load(['cliente.user', 'asignacion']);
        event(new PedidoActualizado($pedido));

        return redirect()->route('repartidor.en-camino', $pedido->ped_id);
    }

    // ── EN CAMINO A TIENDA ────────────────────────────────
    public function enCamino(int $pedidoId)
    {
        $repartidor = $this->getRepartidor();

        $asignacion = AsignacionRepartidor::with(['pedido.tienda', 'pedido.detalles.producto', 'pedido.pago'])
            ->where('asr_fk_repartidor', $repartidor->rep_id)
            ->where('asr_fk_pedido', $pedidoId)
            ->where('asr_estado', 0)
            ->firstOrFail();

        $pedido      = $asignacion->pedido;
        $esEfectivo  = strtolower($pedido->pago?->pag_metodo_pago ?? '') === 'efectivo';
        $montoParaTienda = null;

        if ($esEfectivo) {
            $subtotal        = max(0, $pedido->ped_total - ($pedido->ped_costo_envio ?? 0));
            $pctComision     = \App\Models\ConfiguracionComision::porcentajeActual();
            $montoParaTienda = round($subtotal - round($subtotal * $pctComision / 100, 2), 2);
        }

        return view('repartidor.en-camino', compact('pedido', 'asignacion', 'esEfectivo', 'montoParaTienda'));
    }

    // ── LLEGUÉ A LA TIENDA → checklist ────────────────────
    public function llegueATienda(int $pedidoId)
    {
        $repartidor = $this->getRepartidor();

        $asignacion = AsignacionRepartidor::where('asr_fk_repartidor', $repartidor->rep_id)
            ->where('asr_fk_pedido', $pedidoId)
            ->where('asr_estado', 0)
            ->firstOrFail();

        $asignacion->update(['asr_estado' => 1]);

        EstadoPedido::create([
            'esp_nombre'       => 'en_camino',
            'esp_fecha_cambio' => now(),
            'esp_fk_pedido'    => $pedidoId,
        ]);

        //  Notificar al cliente
        $pedido = Pedido::with(['cliente.user', 'asignacion'])->find($pedidoId);
        event(new PedidoActualizado($pedido));

        return redirect()->route('repartidor.checklist', $pedidoId);
    }

    // ── CHECKLIST ─────────────────────────────────────────
    public function checklist(int $pedidoId)
    {
        $repartidor = $this->getRepartidor();

        AsignacionRepartidor::where('asr_fk_repartidor', $repartidor->rep_id)
            ->where('asr_fk_pedido', $pedidoId)
            ->where('asr_estado', 1)
            ->firstOrFail();

        $pedido = Pedido::with(['tienda', 'detalles.producto', 'cliente.user.persona', 'pago'])
            ->findOrFail($pedidoId);

        $esEfectivo = strtolower($pedido->pago?->pag_metodo_pago ?? '') === 'efectivo';
        $montoParaTienda = null;
        if ($esEfectivo) {
            $subtotal        = max(0, $pedido->ped_total - ($pedido->ped_costo_envio ?? 0));
            $pctComision     = \App\Models\ConfiguracionComision::porcentajeActual();
            $montoParaTienda = round($subtotal - round($subtotal * $pctComision / 100, 2), 2);
        }

        return view('repartidor.checklist', compact('pedido', 'esEfectivo', 'montoParaTienda'));
    }

    // ── RECOGÍ EL PEDIDO → pagar tienda (efectivo) o dirección cliente ──
    public function recogiPedido(int $pedidoId)
    {
        $repartidor = $this->getRepartidor();

        AsignacionRepartidor::where('asr_fk_repartidor', $repartidor->rep_id)
            ->where('asr_fk_pedido', $pedidoId)
            ->where('asr_estado', 1)
            ->firstOrFail();

        $pedido     = Pedido::with('pago')->findOrFail($pedidoId);
        $esEfectivo = strtolower($pedido->pago?->pag_metodo_pago ?? '') === 'efectivo';

        // Efectivo: el repartidor paga a la tienda antes de salir → requiere PIN
        if ($esEfectivo) {
            return redirect()->route('repartidor.pagar-tienda', $pedidoId);
        }

        // Tarjeta: avanzar directamente al cliente
        $asignacion = AsignacionRepartidor::where('asr_fk_repartidor', $repartidor->rep_id)
            ->where('asr_fk_pedido', $pedidoId)
            ->firstOrFail();

        $asignacion->update(['asr_estado' => 2]);

        $pedido->load(['cliente.user', 'asignacion']);
        event(new PedidoActualizado($pedido));

        return redirect()->route('repartidor.entregar', $pedidoId);
    }

    // ── PAGAR A LA TIENDA (efectivo, al recoger) ──────────
    public function pagarTienda(int $pedidoId)
    {
        $repartidor = $this->getRepartidor();

        // Acepta asr_estado=1 (en tienda) o asr_estado=3 (tienda confirmó antes de que el repartidor pagara)
        // siempre que el pago aún no esté liquidado
        $pedido = Pedido::with(['pago', 'tienda'])
            ->whereHas('asignacion', fn($q) => $q
                ->where('asr_fk_repartidor', $repartidor->rep_id)
                ->whereIn('asr_estado', [1, 3])
            )
            ->where('ped_id', $pedidoId)
            ->where(fn($q) => $q
                ->whereNull('ped_estado_liquidacion')
                ->orWhereNotIn('ped_estado_liquidacion', [Pedido::LIQ_LIQUIDADO])
            )
            ->firstOrFail();

        $bloqueado       = $pedido->ped_pin_intentos >= Pedido::PIN_MAX_INTENTOS;
        $subtotal        = max(0, $pedido->ped_total - ($pedido->ped_costo_envio ?? 0));
        $pctComision     = \App\Models\ConfiguracionComision::porcentajeActual();
        $comision        = round($subtotal * $pctComision / 100, 2);
        $montoParaTienda = round($subtotal - $comision, 2);

        return view('repartidor.pagar-tienda', compact('pedido', 'bloqueado', 'montoParaTienda', 'comision', 'pctComision'));
    }

    // ── VALIDAR PIN TIENDA (al recoger, efectivo) ─────────
    public function validarPinTienda(Request $request, int $pedidoId)
    {
        $repartidor = $this->getRepartidor();

        // Acepta asr_estado=1 o 3 (tienda pudo haber cerrado la asignación prematuramente)
        $asignacion = AsignacionRepartidor::where('asr_fk_repartidor', $repartidor->rep_id)
            ->where('asr_fk_pedido', $pedidoId)
            ->whereIn('asr_estado', [1, 3])
            ->latest('asr_fecha')
            ->firstOrFail();

        $pedido = Pedido::with('pago')->findOrFail($pedidoId);

        if ($pedido->ped_pin_intentos >= Pedido::PIN_MAX_INTENTOS) {
            return back()->withErrors(['pin' => 'Has superado el máximo de intentos. Contacta con la tienda.']);
        }

        $request->validate(['pin' => 'required|digits:4']);

        if ($pedido->ped_pin_liquidacion === null) {
            return back()->withErrors(['pin' => 'La tienda aún no ha generado el PIN para este pedido.']);
        }

        if ($request->pin !== $pedido->ped_pin_liquidacion) {
            $pedido->increment('ped_pin_intentos');
            $restantes = Pedido::PIN_MAX_INTENTOS - $pedido->ped_pin_intentos;

            if ($restantes <= 0) {
                return back()->withErrors(['pin' => 'PIN incorrecto. Has agotado todos los intentos. Contacta con la tienda.']);
            }

            return back()->withErrors(['pin' => "PIN incorrecto. Te quedan {$restantes} " . ($restantes === 1 ? 'intento' : 'intentos') . "."]);
        }

        // PIN correcto → registrar pago a tienda, avanzar a entregar
        DB::transaction(function () use ($asignacion, $pedido) {
            // Restablecer asr_estado=2 aunque la tienda lo haya cerrado prematuramente
            $asignacion->update(['asr_estado' => 2]);

            $yaTeniaLiquidado = $pedido->ped_estado_liquidacion === Pedido::LIQ_LIQUIDADO;

            $pedido->update([
                'ped_estado_liquidacion' => Pedido::LIQ_LIQUIDADO,
                'ped_liquidado_at'       => now(),
            ]);

            // Solo distribuir si no se había hecho ya (evita doble distribución)
            if (! $yaTeniaLiquidado) {
                DistribucionPagoService::distribuir($pedido);
            }

            $pedido->load(['cliente.user', 'asignacion']);
            event(new PedidoActualizado($pedido));
        });

        return redirect()->route('repartidor.entregar', $pedidoId);
    }

    // ── ENTREGAR AL CLIENTE ───────────────────────────────
    public function entregar(int $pedidoId)
    {
        $repartidor = $this->getRepartidor();

        AsignacionRepartidor::where('asr_fk_repartidor', $repartidor->rep_id)
            ->where('asr_fk_pedido', $pedidoId)
            ->where('asr_estado', 2)
            ->firstOrFail();

        $pedido = Pedido::with([
            'tienda',
            'detalles.producto',
            'cliente.user.persona',
            'cliente.user.direccions',
            'direccion',
            'pago',
        ])->findOrFail($pedidoId);

        $direccion = $pedido->direccion
            ?? $pedido->cliente?->user?->direccions()->latest('drc_id')->first();

        return view('repartidor.entregar', compact('pedido', 'direccion'));
    }

    // ── CONFIRMÉ ENTREGA ──────────────────────────────────
    public function entreguePedido(int $pedidoId)
    {
        $repartidor = $this->getRepartidor();

        $asignacion = AsignacionRepartidor::where('asr_fk_repartidor', $repartidor->rep_id)
            ->where('asr_fk_pedido', $pedidoId)
            ->where('asr_estado', 2)
            ->firstOrFail();

        $pedido = null;
        $esEfectivo = false;

        DB::transaction(function () use ($asignacion, $pedidoId, $repartidor, &$pedido, &$esEfectivo) {
            $asignacion->update(['asr_estado' => 3]);

            $pedido = Pedido::with('pago')->find($pedidoId);
            $esEfectivo = strtolower($pedido->pago?->pag_metodo_pago ?? '') === 'efectivo';

            $pedido->ped_estado = 'completado';
            $pedido->ped_estado_liquidacion = $esEfectivo
                ? Pedido::LIQ_PENDIENTE
                : Pedido::LIQ_LIQUIDADO;
            $pedido->save();

            EstadoPedido::create([
                'esp_nombre'       => 'completado',
                'esp_fecha_cambio' => now(),
                'esp_fk_pedido'    => $pedidoId,
            ]);

            $pedido->pago?->update(['pag_estado' => 'Aceptado']);

            // Tarjeta → distribuir inmediatamente; Efectivo → esperar PIN
            if (! $esEfectivo) {
                DistribucionPagoService::distribuir($pedido);
            }

            DisponibilidadRepar::updateOrCreate(
                ['dir_fk_repartidor' => $repartidor->rep_id],
                ['dir_estado' => 'disponible', 'dir_actualizacion' => now()]
            );

            $pedido->load(['cliente.user', 'asignacion']);
            event(new PedidoActualizado($pedido));
        });

        if ($esEfectivo) {
            return redirect()->route('repartidor.liquidar', $pedidoId);
        }

        return redirect()->route('repartidor.entrega-ok');
    }

    // ── LLEGUÉ AL CLIENTE ─────────────────────────────────
    public function llegueAlCliente(int $pedidoId)
    {
        $repartidor = $this->getRepartidor();

        $asignacion = AsignacionRepartidor::where('asr_fk_repartidor', $repartidor->rep_id)
            ->where('asr_fk_pedido', $pedidoId)
            ->whereIn('asr_estado', [1, 2, 3])
            ->firstOrFail();

        $pedido     = Pedido::with('pago')->findOrFail($pedidoId);
        $esEfectivo = strtolower($pedido->pago?->pag_metodo_pago ?? '') === 'efectivo';

        // Estado 3 con PIN de entrega ya generado → entrega genuinamente completada.
        if ($asignacion->asr_estado === 3 && $pedido->ped_pin_entrega !== null) {
            return redirect()->route('repartidor.index')
                ->with('success', 'Este pedido ya fue completado anteriormente.');
        }

        // Estado 1 o 3-sin-pin (cierre prematuro por bug): verificar pago efectivo primero.
        if ($asignacion->asr_estado !== 2) {
            if ($esEfectivo && $pedido->ped_estado_liquidacion !== Pedido::LIQ_LIQUIDADO) {
                return redirect()->route('repartidor.pagar-tienda', $pedidoId);
            }

            $asignacion->update(['asr_estado' => 2]);
        }

        return redirect()->route('repartidor.esperar-cliente', $pedidoId);
    }

    // ── ESPERAR AL CLIENTE (PIN) ──────────────────────────
    public function esperarCliente(int $pedidoId)
    {
        $repartidor = $this->getRepartidor();

        AsignacionRepartidor::where('asr_fk_repartidor', $repartidor->rep_id)
            ->where('asr_fk_pedido', $pedidoId)
            ->where('asr_estado', 2)
            ->firstOrFail();

        $pedido = Pedido::with([
            'cliente.user.persona',
            'cliente.user.direccions',
            'direccion',
            'pago',
        ])->findOrFail($pedidoId);

        $persona   = $pedido->cliente?->user?->persona;
        $direccion = $pedido->direccion
            ?? $pedido->cliente?->user?->direccions()->latest('drc_id')->first();
        $bloqueado = $pedido->ped_pin_entrega_intentos >= Pedido::PIN_ENTREGA_MAX_INTENTOS;

        return view('repartidor.esperar-cliente', compact('pedido', 'persona', 'direccion', 'bloqueado'));
    }

    // ── VALIDAR PIN DE ENTREGA ────────────────────────────
    public function validarPinEntrega(Request $request, int $pedidoId)
    {
        $repartidor = $this->getRepartidor();

        $asignacion = AsignacionRepartidor::where('asr_fk_repartidor', $repartidor->rep_id)
            ->where('asr_fk_pedido', $pedidoId)
            ->where('asr_estado', 2)
            ->firstOrFail();

        $pedido = Pedido::with('pago')->findOrFail($pedidoId);

        if ($pedido->ped_pin_entrega_intentos >= Pedido::PIN_ENTREGA_MAX_INTENTOS) {
            return back()->withErrors(['pin' => 'Has superado el máximo de intentos. Contacta con soporte.']);
        }

        $request->validate(['pin' => 'required|digits:4']);

        if (is_null($pedido->ped_pin_entrega)) {
            return back()->withErrors(['pin' => 'El cliente aún no ha generado el código de entrega.']);
        }

        if ($request->pin !== $pedido->ped_pin_entrega) {
            $pedido->increment('ped_pin_entrega_intentos');
            $restantes = Pedido::PIN_ENTREGA_MAX_INTENTOS - $pedido->ped_pin_entrega_intentos;

            if ($restantes <= 0) {
                return back()->withErrors(['pin' => 'Código incorrecto. Has agotado todos los intentos.']);
            }

            return back()->withErrors(['pin' => "Código incorrecto. Te quedan {$restantes} " . ($restantes === 1 ? 'intento' : 'intentos') . "."]);
        }

        // Código correcto → completar entrega
        $esEfectivo = strtolower($pedido->pago?->pag_metodo_pago ?? '') === 'efectivo';

        DB::transaction(function () use ($asignacion, $pedidoId, $repartidor, $pedido, $esEfectivo) {
            $asignacion->update(['asr_estado' => 3]);

            $pedido->ped_estado = 'completado';
            // Efectivo: la tienda ya fue pagada al recoger (LIQ_LIQUIDADO desde validarPinTienda)
            // Tarjeta: liquidar ahora
            if (! $esEfectivo) {
                $pedido->ped_estado_liquidacion = Pedido::LIQ_LIQUIDADO;
            }
            $pedido->save();

            EstadoPedido::create([
                'esp_nombre'       => 'completado',
                'esp_fecha_cambio' => now(),
                'esp_fk_pedido'    => $pedidoId,
            ]);

            $pedido->pago?->update(['pag_estado' => 'Aceptado']);

            // Tarjeta → distribuir ahora; Efectivo → ya distribuido al recoger en tienda
            if (! $esEfectivo) {
                DistribucionPagoService::distribuir($pedido);
            }

            DisponibilidadRepar::updateOrCreate(
                ['dir_fk_repartidor' => $repartidor->rep_id],
                ['dir_estado' => 'disponible', 'dir_actualizacion' => now()]
            );

            $pedido->load(['cliente.user', 'asignacion']);
            event(new PedidoActualizado($pedido));
        });

        return redirect()->route('repartidor.index')
            ->with('success', '¡Pedido entregado! Buen trabajo.');
    }

    // ── LIQUIDAR (mostrar pantalla PIN) ───────────────────
    public function liquidar(int $pedidoId)
    {
        $repartidor = $this->getRepartidor();

        $pedido = Pedido::with(['pago', 'tienda'])
            ->whereHas('asignacion', fn($q) => $q->where('asr_fk_repartidor', $repartidor->rep_id))
            ->where('ped_id', $pedidoId)
            ->where('ped_estado_liquidacion', Pedido::LIQ_PENDIENTE)
            ->firstOrFail();

        $bloqueado = $pedido->ped_pin_intentos >= Pedido::PIN_MAX_INTENTOS;

        // Calcular el monto real que el repartidor debe entregar a la tienda
        // = subtotal productos - comisión de la plataforma
        $subtotal        = max(0, $pedido->ped_total - ($pedido->ped_costo_envio ?? 0));
        $pctComision     = \App\Models\ConfiguracionComision::porcentajeActual();
        $comision        = round($subtotal * $pctComision / 100, 2);
        $montoParaTienda = round($subtotal - $comision, 2);

        return view('repartidor.liquidar', compact('pedido', 'bloqueado', 'montoParaTienda', 'comision', 'pctComision'));
    }

    // ── VALIDAR PIN ────────────────────────────────────────
    public function validarPin(Request $request, int $pedidoId)
    {
        $repartidor = $this->getRepartidor();

        $pedido = Pedido::with('pago')
            ->whereHas('asignacion', fn($q) => $q->where('asr_fk_repartidor', $repartidor->rep_id))
            ->where('ped_id', $pedidoId)
            ->where('ped_estado_liquidacion', Pedido::LIQ_PENDIENTE)
            ->firstOrFail();

        if ($pedido->ped_pin_intentos >= Pedido::PIN_MAX_INTENTOS) {
            return back()->withErrors(['pin' => 'Has superado el máximo de intentos. Contacta con la tienda.']);
        }

        $request->validate(['pin' => 'required|digits:4']);

        if ($pedido->ped_pin_liquidacion === null) {
            return back()->withErrors(['pin' => 'La tienda aún no ha generado el PIN para este pedido.']);
        }

        if ($request->pin !== $pedido->ped_pin_liquidacion) {
            $pedido->increment('ped_pin_intentos');
            $restantes = Pedido::PIN_MAX_INTENTOS - $pedido->ped_pin_intentos;

            if ($restantes <= 0) {
                return back()->withErrors(['pin' => 'PIN incorrecto. Has agotado todos los intentos. Contacta con la tienda.']);
            }

            return back()->withErrors(['pin' => "PIN incorrecto. Te quedan {$restantes} " . ($restantes === 1 ? 'intento' : 'intentos') . "."]);
        }

        // PIN correcto → liquidar
        DB::transaction(function () use ($pedido) {
            $pedido->update([
                'ped_estado_liquidacion' => Pedido::LIQ_LIQUIDADO,
                'ped_liquidado_at'       => now(),
            ]);

            DistribucionPagoService::distribuir($pedido);

            $pedido->load(['cliente.user', 'asignacion']);
            event(new PedidoActualizado($pedido));
        });

        return redirect()->route('repartidor.liquidar.ok', $pedidoId);
    }

    // ── PANTALLA ÉXITO LIQUIDACIÓN ─────────────────────────
    public function liquidarOk(int $pedidoId)
    {
        $repartidor = $this->getRepartidor();

        $pedido = Pedido::with('pago')
            ->whereHas('asignacion', fn($q) => $q->where('asr_fk_repartidor', $repartidor->rep_id))
            ->where('ped_id', $pedidoId)
            ->where('ped_estado_liquidacion', Pedido::LIQ_LIQUIDADO)
            ->firstOrFail();

        $subtotal        = max(0, $pedido->ped_total - ($pedido->ped_costo_envio ?? 0));
        $pctComision     = \App\Models\ConfiguracionComision::porcentajeActual();
        $comision        = round($subtotal * $pctComision / 100, 2);
        $montoParaTienda = round($subtotal - $comision, 2);

        return view('repartidor.liquidar-ok', compact('pedido', 'montoParaTienda', 'comision', 'pctComision'));
    }

    // ── VER ZONA ──────────────────────────────────────────
    public function zona()
    {
        $repartidor = $this->getRepartidor();
        return view('repartidor.zona', compact('repartidor'));
    }

    // ── ACTUALIZAR ZONA ───────────────────────────────────
    public function actualizarZona(Request $request)
    {
        $request->validate([
            'rep_lat'      => 'required|numeric',
            'rep_lng'      => 'required|numeric',
            'rep_ciudad'   => 'required|string|max:150',
            'rep_colonia'  => 'nullable|string|max:200',
            'rep_cp'       => 'nullable|string|max:10',
            'rep_entidad'  => 'nullable|string|max:150',
            'rep_radio_km' => 'required|integer|min:1|max:50',
        ]);

        $repartidor = $this->getRepartidor();

        $repartidor->update($request->only([
            'rep_lat', 'rep_lng', 'rep_ciudad',
            'rep_colonia', 'rep_cp', 'rep_entidad', 'rep_radio_km',
        ]));

        return redirect()->route('repartidor.zona')
            ->with('zona_ok', 'Zona de entrega actualizada correctamente.');
    }

    // ── MIS GANANCIAS ─────────────────────────────────────
    public function ganancias()
    {
        $repartidor = $this->getRepartidor();

        $wallet = \App\Models\Wallet::with(['movimientos' => fn($q) => $q->with('pedido.pago')->orderBy('mwl_fecha', 'desc')->limit(30)])
            ->where('wal_tipo', 'repartidor')
            ->where('wal_fk_repartidor', $repartidor->rep_id)
            ->first();

        $deudas = \App\Models\DeudaRepartidor::where('dre_fk_repartidor', $repartidor->rep_id)
            ->where('dre_estado', 'pendiente')
            ->with('pedido')
            ->orderBy('dre_fecha', 'desc')
            ->get();

        return view('repartidor.ganancias', compact('wallet', 'deudas', 'repartidor'));
    }

    // ── HISTORIAL ─────────────────────────────────────────
    public function historial()
    {
        $repartidor = $this->getRepartidor();

        $asignaciones = AsignacionRepartidor::with([
            'pedido.tienda',
            'pedido.cliente.user.persona',
        ])
            ->where('asr_fk_repartidor', $repartidor->rep_id)
            ->where('asr_estado', 3)
            ->latest('asr_fecha')
            ->get();

        return view('repartidor.historial', compact('asignaciones'));
    }

    // ── CANCELAR ENTREGA (antes de llegar a la tienda) ────
    public function cancelarEntrega(Request $request, int $pedidoId)
    {
        $request->validate([
            'motivo' => ['required', 'string', 'min:5', 'max:500'],
        ]);

        $repartidor = $this->getRepartidor();

        $asignacion = AsignacionRepartidor::where('asr_fk_repartidor', $repartidor->rep_id)
            ->where('asr_fk_pedido', $pedidoId)
            ->where('asr_estado', 0) // solo si aún no llegó a la tienda
            ->firstOrFail();

        DB::transaction(function () use ($asignacion, $repartidor, $request) {
            // Marcar asignación como cancelada (-1) pero conservar el registro
            $asignacion->update([
                'asr_estado'             => -1,
                'asr_motivo_cancelacion' => $request->motivo,
            ]);

            // Repartidor vuelve a estar disponible
            DisponibilidadRepar::updateOrCreate(
                ['dir_fk_repartidor' => $repartidor->rep_id],
                ['dir_estado' => 'disponible', 'dir_actualizacion' => now()]
            );
        });

        return redirect()->route('repartidor.index')
            ->with('success', 'Entrega cancelada. El pedido quedará disponible para otro repartidor.');
    }
}
