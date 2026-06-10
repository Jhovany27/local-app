<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('repartidor', function (Blueprint $table) {
            $table->string('rep_colonia', 200)->nullable()->after('rep_ciudad');
            $table->decimal('rep_lat', 10, 7)->nullable()->after('rep_colonia');
            $table->decimal('rep_lng', 10, 7)->nullable()->after('rep_lat');
            $table->tinyInteger('rep_radio_km')->default(10)->after('rep_lng');
        });
    }

    public function down(): void
    {
        Schema::table('repartidor', function (Blueprint $table) {
            $table->dropColumn(['rep_colonia', 'rep_lat', 'rep_lng', 'rep_radio_km']);
        });
    }
};
