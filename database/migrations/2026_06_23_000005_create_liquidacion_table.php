<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('liquidacion', function (Blueprint $table) {
            $table->id('liq_id');
            $table->string('liq_tipo', 20);                           // tienda | repartidor
            $table->integer('liq_fk_tienda')->nullable();
            $table->integer('liq_fk_repartidor')->nullable();
            $table->decimal('liq_monto', 10, 2);
            $table->date('liq_periodo_inicio');
            $table->date('liq_periodo_fin');
            $table->string('liq_estado', 20)->default('pendiente');   // pendiente | pagada
            $table->datetime('liq_fecha_creacion');
            $table->datetime('liq_fecha_pago')->nullable();
            $table->text('liq_notas')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liquidacion');
    }
};
