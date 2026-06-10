<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('configuracion_envio')) {
            Schema::create('configuracion_envio', function (Blueprint $table) {
                $table->id();
                $table->decimal('tarifa_base', 8, 2)->default(15.00);
                $table->decimal('precio_por_km', 8, 2)->default(5.00);
                $table->timestamps();
            });

            DB::table('configuracion_envio')->insert([
                'tarifa_base'   => 15.00,
                'precio_por_km' => 5.00,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion_envio');
    }
};
