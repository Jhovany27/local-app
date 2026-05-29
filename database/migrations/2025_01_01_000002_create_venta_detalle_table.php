<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venta_detalle', function (Blueprint $table) {
            $table->increments('vde_id');
            $table->integer('vde_cantidad');
            $table->decimal('vde_precio_unitario', 10, 2);
            $table->decimal('vde_subtotal', 10, 2);
            $table->unsignedInteger('vde_fk_venta');
            $table->integer('vde_fk_producto');

            $table->foreign('vde_fk_venta')
                ->references('ven_id')
                ->on('venta')
                ->onDelete('cascade');

            $table->foreign('vde_fk_producto')
                ->references('pro_id')  // ajusta si tu PK de producto es diferente
                ->on('producto')         // ajusta si tu tabla se llama diferente
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venta_detalle');
    }
};
