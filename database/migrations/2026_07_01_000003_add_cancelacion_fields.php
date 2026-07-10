<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedido', function (Blueprint $table) {
            $table->text('ped_motivo_cancelacion')->nullable()->after('ped_estado');
            $table->string('ped_cancelado_por', 20)->nullable()->after('ped_motivo_cancelacion');
        });

        Schema::table('asignacion_repartidor', function (Blueprint $table) {
            $table->text('asr_motivo_cancelacion')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pedido', function (Blueprint $table) {
            $table->dropColumn(['ped_motivo_cancelacion', 'ped_cancelado_por']);
        });

        Schema::table('asignacion_repartidor', function (Blueprint $table) {
            $table->dropColumn('asr_motivo_cancelacion');
        });
    }
};
