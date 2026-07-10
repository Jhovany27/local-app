<?php

use App\Models\AsignacionRepartidor;

$rows = AsignacionRepartidor::with(['pedido.tienda'])
    ->whereIn('asr_estado', [0, 1, 2])
    ->get();

foreach ($rows as $a) {
    $tienda    = $a->pedido && $a->pedido->tienda ? $a->pedido->tienda->tie_nombre : 'N/A';
    $pedEstado = $a->pedido ? $a->pedido->ped_estado : 'N/A';
    echo $a->asr_id . ' | estado:' . $a->asr_estado . ' | tienda:' . $tienda . ' | ped_estado:' . $pedEstado . ' | rep_id:' . $a->asr_fk_repartidor . "\n";
}
