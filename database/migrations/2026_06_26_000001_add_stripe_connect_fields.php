<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tienda', function (Blueprint $table) {
            $table->string('stripe_account_id')->nullable()->after('tie_numero_cuenta');
        });

        Schema::table('repartidor', function (Blueprint $table) {
            $table->string('stripe_account_id')->nullable()->after('rep_numero_cuenta');
        });

        Schema::table('liquidacion', function (Blueprint $table) {
            $table->string('stripe_transfer_id')->nullable()->after('liq_notas');
        });
    }

    public function down(): void
    {
        Schema::table('tienda',      fn($t) => $t->dropColumn('stripe_account_id'));
        Schema::table('repartidor',  fn($t) => $t->dropColumn('stripe_account_id'));
        Schema::table('liquidacion', fn($t) => $t->dropColumn('stripe_transfer_id'));
    }
};
