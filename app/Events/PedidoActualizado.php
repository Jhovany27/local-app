<?php

namespace App\Events;

use App\Models\AsignacionRepartidor;
use App\Models\Pedido;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PedidoActualizado implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int    $pedidoId;
    public string $estado;
    public string $tipoEntrega;
    public ?int   $asrEstado;
    public string $badgeLabel;
    public string $badgeClass;
    public int    $clienteUserId;

    public function __construct(Pedido $pedido)
    {
        $this->pedidoId      = $pedido->ped_id;
        $this->estado        = $pedido->ped_estado;
        $this->tipoEntrega   = strtolower($pedido->ped_tipo_entrega ?? '');
        $this->clienteUserId = $pedido->cliente?->user_id ?? 0;

        // Cargar asignación
        $asignacion    = AsignacionRepartidor::where('asr_fk_pedido', $pedido->ped_id)
            ->whereIn('asr_estado', [0, 1, 2, 3])
            ->latest('asr_fecha')
            ->first();

        $this->asrEstado = $asignacion ? (int)$asignacion->asr_estado : null;

        // Calcular badge igual que en el blade
        [$this->badgeLabel, $this->badgeClass] = $this->calcularBadge();
    }

    private function calcularBadge(): array
    {
        $esDomicilio = $this->tipoEntrega === 'domicilio';
        $asr = $this->asrEstado;

        return match (true) {
            $this->estado === 'cancelado'    => ['Cancelado', 'badge-cancelado'],
            $this->estado === 'completado'   => ['Completado', 'badge-completado'],
            $this->estado === 'pendiente'    => ['Pendiente', 'badge-pendiente'],
            $this->estado === 'en_preparacion' => ['En preparación', 'badge-preparacion'],
            $this->estado === 'listo' && !$esDomicilio => ['Listo para recoger', 'badge-listo-tienda'],
            $this->estado === 'listo' && $esDomicilio && $asr === null => ['Buscando repartidor', 'badge-listo-tienda'],
            $this->estado === 'listo' && $esDomicilio && $asr === 0   => ['Repartidor en camino', 'badge-repartidor-yendo'],
            $this->estado === 'listo' && $esDomicilio && $asr === 1   => ['Repartidor recogiendo', 'badge-repartidor-recoge'],
            $this->estado === 'listo' && $esDomicilio && $asr === 2   => ['En camino a ti', 'badge-repartidor-camino'],
            default => ['Completado', 'badge-completado'],
        };
    }

    public function broadcastOn(): array
    {
        // Canal privado por usuario — solo el cliente dueño del pedido lo recibe
        return [
            new Channel("pedido.{$this->clienteUserId}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'pedido.actualizado';
    }
}
