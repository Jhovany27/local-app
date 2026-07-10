<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarjetas_cliente', function (Blueprint $table) {
            $table->id('tar_id');
            $table->foreignId('tar_fk_user')->constrained('users')->onDelete('cascade');
            $table->string('tar_stripe_pm_id')->unique();
            $table->string('tar_brand', 50);
            $table->char('tar_last4', 4);
            $table->unsignedTinyInteger('tar_exp_month');
            $table->unsignedSmallInteger('tar_exp_year');
            $table->boolean('tar_es_default')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarjetas_cliente');
    }
};
