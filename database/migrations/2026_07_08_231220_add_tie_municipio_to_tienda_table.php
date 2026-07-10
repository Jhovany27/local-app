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
            $table->string('tie_municipio', 150)->nullable()->after('tie_direccion');
        });
    }

    public function down(): void
    {
        Schema::table('tienda', function (Blueprint $table) {
            $table->dropColumn('tie_municipio');
        });
    }
};
