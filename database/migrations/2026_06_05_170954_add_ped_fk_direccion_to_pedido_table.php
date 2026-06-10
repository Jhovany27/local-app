<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('pedido', 'ped_fk_direccion')) {
            Schema::table('pedido', function (Blueprint $table) {
                $table->unsignedBigInteger('ped_fk_direccion')->nullable()->after('ped_costo_envio');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedido', function (Blueprint $table) {
            //
        });
    }
};
