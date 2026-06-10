<?php

namespace App\Http\Controllers;

use App\Events\PedidoActualizado;
use App\Models\AsignacionRepartidor;
use App\Models\DisponibilidadRepar;
use App\Models\EstadoPedido;
use App\Models\Pedido;
use App\Models\Repartidor;
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

        return view('repartidor.index', compact('pedidos', 'pedidoActivo', 'asignacionActiva', 'repartidor', 'disponibilidad'));
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
                // Disponible (sin asignación activa) o ya asignado a este repartidor
                $q->whereDoesntHave('asignacion')
                  ->orWhereHas('asignacion', fn($q2) => $q2->where('asr_fk_repartidor', $repartidor->rep_id));
            })
            ->firstOrFail();

        $direccion = $pedido->direccion
            ?? $pedido->cliente?->user?->direccions()->latest('drc_id')->first();

        return view('repartidor.pedido', compact('pedido', 'direccion'));
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

        $asignacion = AsignacionRepartidor::with(['pedido.tienda', 'pedido.detalles.producto'])
            ->where('asr_fk_repartidor', $repartidor->rep_id)
            ->where('asr_fk_pedido', $pedidoId)
            ->where('asr_estado', 0)
            ->firstOrFail();

        $pedido = $asignacion->pedido;
        return view('repartidor.en-camino', compact('pedido', 'asignacion'));
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

        $pedido = Pedido::with(['tienda', 'detalles.producto', 'cliente.user.persona'])
            ->findOrFail($pedidoId);

        return view('repartidor.checklist', compact('pedido'));
    }

    // ── RECOGÍ EL PEDIDO → dirección cliente ──────────────
    public function recogiPedido(int $pedidoId)
    {
        $repartidor = $this->getRepartidor();

        $asignacion = AsignacionRepartidor::where('asr_fk_repartidor', $repartidor->rep_id)
            ->where('asr_fk_pedido', $pedidoId)
            ->where('asr_estado', 1)
            ->firstOrFail();

        $asignacion->update(['asr_estado' => 2]);

        //  Notificar al cliente
        $pedido = Pedido::with(['cliente.user', 'asignacion'])->find($pedidoId);
        event(new PedidoActualizado($pedido));

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

        DB::transaction(function () use ($asignacion, $pedidoId, $repartidor) {
            $asignacion->update(['asr_estado' => 3]);

            $pedido = Pedido::find($pedidoId);
            $pedido->ped_estado = 'completado';
            $pedido->save();

            EstadoPedido::create([
                'esp_nombre'       => 'completado',
                'esp_fecha_cambio' => now(),
                'esp_fk_pedido'    => $pedidoId,
            ]);

            $pedido->pago?->update(['pag_estado' => 'Aceptado']);

            DisponibilidadRepar::updateOrCreate(
                ['dir_fk_repartidor' => $repartidor->rep_id],
                ['dir_estado' => 'disponible', 'dir_actualizacion' => now()]
            );

            //  Notificar al cliente
            $pedido->load(['cliente.user', 'asignacion']);
            event(new PedidoActualizado($pedido));
        });

        return redirect()->route('repartidor.index')
            ->with('success', '¡Pedido entregado! Buen trabajo.');
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
}
