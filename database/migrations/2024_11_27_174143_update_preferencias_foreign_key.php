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
        Schema::table('preferencias', function (Blueprint $table) {
            // Eliminar la clave foránea existente
            $table->dropForeign(['emprendimiento_id']);
            
            // Crear una nueva clave foránea con ON DELETE CASCADE
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
        Schema::table('preferencias', function (Blueprint $table) {
            // Restaurar la clave foránea original sin cascada
            $table->dropForeign(['emprendimiento_id']);
            $table->foreign('emprendimiento_id')
                  ->references('id')
                  ->on('emprendimientos');
        });
    }
};
