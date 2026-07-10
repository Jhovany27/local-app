<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LogAuditoria extends Model
{
    protected $table      = 'log_auditoria';
    protected $primaryKey = 'log_id';
    public $timestamps    = false;

    protected $casts = [
        'log_datos' => 'array',
        'log_fecha' => 'datetime',
    ];

    protected $fillable = [
        'log_user_id',
        'log_accion',
        'log_descripcion',
        'log_datos',
        'log_fecha',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'log_user_id');
    }

    public static function registrar(string $accion, string $descripcion, array $datos = []): void
    {
        static::create([
            'log_user_id'    => Auth::id(),
            'log_accion'     => $accion,
            'log_descripcion'=> $descripcion,
            'log_datos'      => $datos ?: null,
            'log_fecha'      => now(),
        ]);
    }
}
