<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracion_comision', function (Blueprint $table) {
            $table->id('com_id');
            $table->decimal('com_porcentaje', 5, 2)->default(10.00);
            $table->boolean('com_activa')->default(true);
            $table->datetime('com_fecha')->useCurrent();
        });

        // Insertar configuración por defecto (10%)
        DB::table('configuracion_comision')->insert([
            'com_porcentaje' => 10.00,
            'com_activa'     => true,
            'com_fecha'      => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion_comision');
    }
};
