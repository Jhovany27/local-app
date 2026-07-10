<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimiento_wallet', function (Blueprint $table) {
            $table->id('mwl_id');
            $table->unsignedBigInteger('mwl_fk_wallet');
            $table->string('mwl_tipo', 30);          // abono | retiro | comision
            $table->decimal('mwl_monto', 10, 2);
            $table->string('mwl_descripcion')->nullable();
            $table->integer('mwl_fk_pedido')->nullable();
            $table->datetime('mwl_fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimiento_wallet');
    }
};
