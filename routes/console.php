<?php

use App\Models\ConfiguracionComision;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Liquidaciones periódicas: se ejecuta diariamente pero el comando
// calcula el período según la frecuencia configurada en BD.
Schedule::command('liquidaciones:generar')
    ->dailyAt('06:00')
    ->timezone('America/Mexico_City')
    ->when(function () {
        $dias = ConfiguracionComision::frecuenciaDias();
        // Solo ejecutar si hoy corresponde a un día de corte
        // (cada N días desde el 1 de enero como referencia)
        return now()->dayOfYear % $dias === 0;
    });
