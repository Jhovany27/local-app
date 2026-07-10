<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('configuracion_comision', function (Blueprint $table) {
            // Días entre cortes (7 = semanal, 14 = quincenal, 30 = mensual)
            $table->unsignedTinyInteger('frecuencia_liquidacion_dias')->default(7)->after('limite_deuda');
        });
    }

    public function down(): void
    {
        Schema::table('configuracion_comision', function (Blueprint $table) {
            $table->dropColumn('frecuencia_liquidacion_dias');
        });
    }
};
