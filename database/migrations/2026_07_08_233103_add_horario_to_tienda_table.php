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
        Schema::table('tienda', function (Blueprint $table) {
            $table->time('tie_hora_apertura')->nullable()->after('tie_municipio');
            $table->time('tie_hora_cierre')->nullable()->after('tie_hora_apertura');
        });
    }

    public function down(): void
    {
        Schema::table('tienda', function (Blueprint $table) {
            $table->dropColumn(['tie_hora_apertura', 'tie_hora_cierre']);
        });
    }
};
