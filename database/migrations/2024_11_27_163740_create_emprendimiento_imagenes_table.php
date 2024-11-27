<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmprendimientoImagenesTable extends Migration
{
    public function up()
    {
        Schema::create('emprendimiento_imagenes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('emprendimiento_id'); // ID del emprendimiento
            $table->string('path'); // Ruta de la imagen
            $table->timestamps();

            // Relación con la tabla 'emprendimientos'
            $table->foreign('emprendimiento_id')
                  ->references('id')
                  ->on('emprendimientos')
                  ->onDelete('cascade'); // Eliminar imágenes si se elimina el emprendimiento
        });
    }

    public function down()
    {
        Schema::dropIfExists('emprendimiento_imagenes');
    }
}
