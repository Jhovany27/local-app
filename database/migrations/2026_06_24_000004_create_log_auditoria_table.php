<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_auditoria', function (Blueprint $table) {
            $table->id('log_id');
            $table->unsignedBigInteger('log_user_id')->nullable();
            $table->string('log_accion', 100);
            $table->text('log_descripcion')->nullable();
            $table->json('log_datos')->nullable();
            $table->datetime('log_fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_auditoria');
    }
};
