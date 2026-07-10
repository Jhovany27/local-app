<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pago MODIFY COLUMN pag_estado ENUM('Pendiente','Aceptado','Rechazado','Reembolsado') NOT NULL DEFAULT 'Pendiente'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pago MODIFY COLUMN pag_estado ENUM('Pendiente','Aceptado','Rechazado') NOT NULL DEFAULT 'Pendiente'");
    }
};
