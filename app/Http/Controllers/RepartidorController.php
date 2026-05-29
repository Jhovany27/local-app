<?php

namespace App\Http\Controllers;

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

        $pedidos = Pedido::with(['tienda', 'detalles'])
            ->where('ped_estado', 'listo')
            ->where('ped_tipo_entrega', 'domicilio')
            ->whereDoesntHave('asignacion', fn($q) => $q->whereIn('asr_estado', [0, 1, 2]))
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
        $pedido = Pedido::with(['tienda', 'detalles.producto', 'cliente.user.persona', 'pago'])
            ->where('ped_id', $pedidoId)
            ->where('ped_estado', 'listo')
            ->where('ped_tipo_entrega', 'domicilio')
            ->firstOrFail();

        return view('repartidor.pedido', compact('pedido'));
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
            'asr_estado'        => 0, // yendo a tienda
            'asr_fk_repartidor' => $repartidor->rep_id,
            'asr_fk_pedido'     => $pedido->ped_id,
        ]);

        DisponibilidadRepar::updateOrCreate(
            ['dir_fk_repartidor' => $repartidor->rep_id],
            ['dir_estado' => 'ocupado', 'dir_actualizacion' => now()]
        );

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

        $asignacion->update(['asr_estado' => 1]); // recogiendo

        EstadoPedido::create([
            'esp_nombre'       => 'en_camino',
            'esp_fecha_cambio' => now(),
            'esp_fk_pedido'    => $pedidoId,
        ]);

        return redirect()->route('repartidor.checklist', $pedidoId);
    }

    // ── CHECKLIST ─────────────────────────────────────────
    public function checklist(int $pedidoId)
    {
        $repartidor = $this->getRepartidor();

        $asignacion = AsignacionRepartidor::where('asr_fk_repartidor', $repartidor->rep_id)
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

        $asignacion->update(['asr_estado' => 2]); // entregando

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
            'pago',
        ])->findOrFail($pedidoId);

        // Buscar dirección activa del cliente
        $direccion = $pedido->cliente?->user?->direccions()
            ->latest('drc_id')
            ->first();

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
            $asignacion->update(['asr_estado' => 3]); // completado

            $pedido = Pedido::find($pedidoId);
            $pedido->update(['ped_estado' => 'completado']);

            EstadoPedido::create([
                'esp_nombre'       => 'completado',
                'esp_fecha_cambio' => now(),
                'esp_fk_pedido'    => $pedidoId,
            ]);

            $pedido->pago?->update(['pag_estado' => 'Aceptado']);

            // Liberar repartidor
            DisponibilidadRepar::updateOrCreate(
                ['dir_fk_repartidor' => $repartidor->rep_id],
                ['dir_estado' => 'disponible', 'dir_actualizacion' => now()]
            );
        });

        return redirect()->route('repartidor.index')
            ->with('success', '¡Pedido entregado! Buen trabajo.');
    }

    public function historial()
    {
        $repartidor = $this->getRepartidor();

        $asignaciones = AsignacionRepartidor::with([
            'pedido.tienda',
            'pedido.cliente.user.persona',
        ])
            ->where('asr_fk_repartidor', $repartidor->rep_id)
            ->where('asr_estado', 3) // completado
            ->latest('asr_fecha')
            ->get();

        return view('repartidor.historial', compact('asignaciones'));
    }
}
