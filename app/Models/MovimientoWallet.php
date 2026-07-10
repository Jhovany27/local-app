<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoWallet extends Model
{
    protected $table      = 'movimiento_wallet';
    protected $primaryKey = 'mwl_id';
    public $timestamps    = false;

    protected $casts = [
        'mwl_monto' => 'float',
        'mwl_fecha' => 'datetime',
    ];

    protected $fillable = [
        'mwl_fk_wallet',
        'mwl_tipo',
        'mwl_monto',
        'mwl_descripcion',
        'mwl_fk_pedido',
        'mwl_fecha',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'mwl_fk_wallet', 'wal_id');
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'mwl_fk_pedido', 'ped_id');
    }
}
