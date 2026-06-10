<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('repartidor', function (Blueprint $table) {
            $table->string('rep_ciudad', 150)->nullable()->after('rep_motivo_rechazo');
        });
    }

    public function down(): void
    {
        Schema::table('repartidor', function (Blueprint $table) {
            $table->dropColumn('rep_ciudad');
        });
    }
};
