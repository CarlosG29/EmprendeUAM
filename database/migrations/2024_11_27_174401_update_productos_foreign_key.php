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
        Schema::table('productos', function (Blueprint $table) {
            // Elimina la clave foránea existente
            $table->dropForeign(['emprendimiento_id']);

            // Agrega una nueva clave foránea con ON DELETE CASCADE
            $table->foreign('emprendimiento_id')
                  ->references('id')
                  ->on('emprendimientos')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Elimina la clave foránea con cascada
            $table->dropForeign(['emprendimiento_id']);

            // Agrega nuevamente la clave foránea sin cascada
            $table->foreign('emprendimiento_id')
                  ->references('id')
                  ->on('emprendimientos');
        });
    }
};
