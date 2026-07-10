<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('configuracion_comision', function (Blueprint $table) {
            $table->decimal('limite_deuda', 10, 2)->default(500.00)->after('com_porcentaje');
        });
    }

    public function down(): void
    {
        Schema::table('configuracion_comision', function (Blueprint $table) {
            $table->dropColumn('limite_deuda');
        });
    }
};
