<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEmprendimientosTable extends Migration
{
    public function up()
    {
        Schema::table('emprendimientos', function (Blueprint $table) {
            $table->unsignedBigInteger('emprendedor_id')->nullable(false)->change();
            $table->foreign('emprendedor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('emprendimientos', function (Blueprint $table) {
            $table->dropForeign(['emprendedor_id']);
            $table->unsignedBigInteger('emprendedor_id')->nullable()->change();
        });
    }
}
