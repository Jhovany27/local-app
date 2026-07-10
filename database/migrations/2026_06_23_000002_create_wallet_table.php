<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('wallet');

        Schema::create('wallet', function (Blueprint $table) {
            $table->id('wal_id');
            $table->string('wal_tipo', 20);                    // tienda | repartidor
            $table->integer('wal_fk_tienda')->nullable();
            $table->integer('wal_fk_repartidor')->nullable();
            $table->decimal('wal_saldo_disponible', 10, 2)->default(0);
            $table->decimal('wal_saldo_pendiente',  10, 2)->default(0);

            $table->unique(['wal_tipo', 'wal_fk_tienda']);
            $table->unique(['wal_tipo', 'wal_fk_repartidor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet');
    }
};
