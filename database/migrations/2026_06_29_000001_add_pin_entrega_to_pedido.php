<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedido', function (Blueprint $table) {
            $table->string('ped_pin_entrega', 4)->nullable()->after('ped_pin_liquidacion');
            $table->unsignedTinyInteger('ped_pin_entrega_intentos')->default(0)->after('ped_pin_entrega');
        });
    }

    public function down(): void
    {
        Schema::table('pedido', function (Blueprint $table) {
            $table->dropColumn(['ped_pin_entrega', 'ped_pin_entrega_intentos']);
        });
    }
};
