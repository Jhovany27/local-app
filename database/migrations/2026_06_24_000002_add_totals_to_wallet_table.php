<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wallet', function (Blueprint $table) {
            $table->decimal('wal_total_ventas',      10, 2)->default(0)->after('wal_saldo_pendiente');
            $table->decimal('wal_total_comisiones',  10, 2)->default(0)->after('wal_total_ventas');
            $table->decimal('wal_total_liquidado',   10, 2)->default(0)->after('wal_total_comisiones');
        });
    }

    public function down(): void
    {
        Schema::table('wallet', function (Blueprint $table) {
            $table->dropColumn(['wal_total_ventas', 'wal_total_comisiones', 'wal_total_liquidado']);
        });
    }
};
