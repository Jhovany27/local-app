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
        if (!Schema::hasColumn('pedido', 'ped_costo_envio')) {
            Schema::table('pedido', function (Blueprint $table) {
                $table->decimal('ped_costo_envio', 8, 2)->default(0)->after('ped_total');
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
