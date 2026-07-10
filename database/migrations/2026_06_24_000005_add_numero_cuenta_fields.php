<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tienda', function (Blueprint $table) {
            $table->string('tie_numero_cuenta', 30)->nullable()->after('tie_direccion');
        });

        Schema::table('repartidor', function (Blueprint $table) {
            $table->string('rep_numero_cuenta', 30)->nullable()->after('rep_tipo_vehiculo');
        });
    }

    public function down(): void
    {
        Schema::table('tienda', function (Blueprint $table) {
            $table->dropColumn('tie_numero_cuenta');
        });
        Schema::table('repartidor', function (Blueprint $table) {
            $table->dropColumn('rep_numero_cuenta');
        });
    }
};
