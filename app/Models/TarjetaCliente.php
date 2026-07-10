<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarjetaCliente extends Model
{
    protected $table      = 'tarjetas_cliente';
    protected $primaryKey = 'tar_id';

    protected $fillable = [
        'tar_fk_user',
        'tar_stripe_pm_id',
        'tar_brand',
        'tar_last4',
        'tar_exp_month',
        'tar_exp_year',
        'tar_es_default',
    ];

    protected $casts = [
        'tar_es_default' => 'boolean',
        'tar_exp_month'  => 'integer',
        'tar_exp_year'   => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'tar_fk_user');
    }

    public function brandLabel(): string
    {
        return match (strtolower($this->tar_brand)) {
            'visa'       => 'Visa',
            'mastercard' => 'Mastercard',
            'amex'       => 'American Express',
            'discover'   => 'Discover',
            'diners'     => 'Diners Club',
            'unionpay'   => 'UnionPay',
            default      => ucfirst($this->tar_brand),
        };
    }
}
