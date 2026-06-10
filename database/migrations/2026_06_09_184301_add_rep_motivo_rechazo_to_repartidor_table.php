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
        Schema::table('repartidor', function (Blueprint $table) {
            $table->text('rep_motivo_rechazo')->nullable()->after('rep_estado');
        });
    }

    public function down(): void
    {
        Schema::table('repartidor', function (Blueprint $table) {
            $table->dropColumn('rep_motivo_rechazo');
        });
    }
};
