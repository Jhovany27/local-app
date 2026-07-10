<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedido', function (Blueprint $table) {
            $table->string('ped_estado_liquidacion', 30)->default('no_aplica')->after('ped_estado');
            $table->string('ped_pin_liquidacion', 4)->nullable()->after('ped_estado_liquidacion');
            $table->timestamp('ped_pin_generado_at')->nullable()->after('ped_pin_liquidacion');
            $table->tinyInteger('ped_pin_intentos')->default(0)->after('ped_pin_generado_at');
            $table->timestamp('ped_liquidado_at')->nullable()->after('ped_pin_intentos');
        });
    }

    public function down(): void
    {
        Schema::table('pedido', function (Blueprint $table) {
            $table->dropColumn([
                'ped_estado_liquidacion',
                'ped_pin_liquidacion',
                'ped_pin_generado_at',
                'ped_pin_intentos',
                'ped_liquidado_at',
            ]);
        });
    }
};
