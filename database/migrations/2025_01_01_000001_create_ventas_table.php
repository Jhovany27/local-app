<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venta', function (Blueprint $table) {
            $table->increments('ven_id');
            $table->dateTime('ven_fecha');
            $table->decimal('ven_total', 10, 2)->default(0);
            $table->tinyInteger('ven_estado')->default(0)->comment('0=pendiente, 1=completada, 2=cancelada');
            $table->integer('ven_fk_tienda');

            $table->foreign('ven_fk_tienda')
                ->references('tie_id')
                ->on('tienda')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venta');
    }
};
