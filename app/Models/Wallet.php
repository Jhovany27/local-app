<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table      = 'wallet';
    protected $primaryKey = 'wal_id';
    public $timestamps    = false;

    protected $casts = [
        'wal_saldo_disponible' => 'float',
        'wal_saldo_pendiente'  => 'float',
        'wal_total_ventas'     => 'float',
        'wal_total_comisiones' => 'float',
        'wal_total_liquidado'  => 'float',
    ];

    protected $fillable = [
        'wal_tipo',
        'wal_fk_tienda',
        'wal_fk_repartidor',
        'wal_saldo_disponible',
        'wal_saldo_pendiente',
        'wal_total_ventas',
        'wal_total_comisiones',
        'wal_total_liquidado',
    ];

    public function tienda()
    {
        return $this->belongsTo(Tienda::class, 'wal_fk_tienda', 'tie_id');
    }

    public function repartidor()
    {
        return $this->belongsTo(Repartidor::class, 'wal_fk_repartidor', 'rep_id');
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoWallet::class, 'mwl_fk_wallet', 'wal_id');
    }

    public function getNombreOwnerAttribute(): string
    {
        if ($this->wal_tipo === 'tienda') {
            return $this->tienda?->tie_nombre ?? '—';
        }
        $persona = $this->repartidor?->user?->persona;
        return trim(($persona?->per_nombre ?? '') . ' ' . ($persona?->per_paterno ?? '')) ?: '—';
    }

    // Helpers para obtener o crear wallets
    public static function deTienda(int $tiendaId): self
    {
        return static::firstOrCreate(
            ['wal_tipo' => 'tienda', 'wal_fk_tienda' => $tiendaId],
            ['wal_saldo_disponible' => 0, 'wal_saldo_pendiente' => 0]
        );
    }

    public static function deRepartidor(int $repartidorId): self
    {
        return static::firstOrCreate(
            ['wal_tipo' => 'repartidor', 'wal_fk_repartidor' => $repartidorId],
            ['wal_saldo_disponible' => 0, 'wal_saldo_pendiente' => 0]
        );
    }

    public function getSaldoTotalAttribute(): float
    {
        return $this->wal_saldo_disponible + $this->wal_saldo_pendiente;
    }
}
