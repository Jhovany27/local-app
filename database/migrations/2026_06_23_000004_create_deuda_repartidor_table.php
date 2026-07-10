<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deuda_repartidor', function (Blueprint $table) {
            $table->id('dre_id');
            $table->integer('dre_fk_repartidor');
            $table->integer('dre_fk_pedido');
            $table->decimal('dre_monto', 10, 2);
            $table->string('dre_estado', 20)->default('pendiente'); // pendiente | pagada
            $table->datetime('dre_fecha');
            $table->datetime('dre_fecha_pago')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deuda_repartidor');
    }
};
