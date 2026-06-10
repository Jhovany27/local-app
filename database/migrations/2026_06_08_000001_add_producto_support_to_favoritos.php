<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('favorito', 'fav_fk_producto')) {
            Schema::table('favorito', function (Blueprint $table) {
                $table->integer('fav_fk_producto')->nullable()->after('fav_fk_tienda');
            });
        }

        if (!Schema::hasColumn('favorito', 'fav_fk_producto')) {
            return;
        }

        // Verificar si la FK ya existe antes de crearla
        $connection = Schema::getConnection();
        $constraints = $connection->select(
            "SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
             WHERE TABLE_NAME = 'favorito' AND COLUMN_NAME = 'fav_fk_producto' AND TABLE_SCHEMA = ?"
            ,
            [env('DB_DATABASE')]
        );

        if (empty($constraints)) {
            Schema::table('favorito', function (Blueprint $table) {
                $table->foreign('fav_fk_producto')->references('pro_id')->on('producto')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('favorito', 'fav_fk_producto')) {
            Schema::table('favorito', function (Blueprint $table) {
                // Verificar si la FK existe antes de intentar borrarla
                $connection = Schema::getConnection();
                $constraints = $connection->select(
                    "SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                     WHERE TABLE_NAME = 'favorito' AND COLUMN_NAME = 'fav_fk_producto' AND TABLE_SCHEMA = ?"
                    ,
                    [env('DB_DATABASE')]
                );

                if (!empty($constraints)) {
                    $table->dropForeignIdFor(\App\Models\Producto::class, 'fav_fk_producto');
                }
            });

            Schema::table('favorito', function (Blueprint $table) {
                $table->dropColumn('fav_fk_producto');
            });
        }
    }
};
