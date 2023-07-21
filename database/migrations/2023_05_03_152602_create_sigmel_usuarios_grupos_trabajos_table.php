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

        Schema::connection('sigmel_gestiones')->create('sigmel_usuarios_grupos_trabajos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_equipo_trabajo');
            $table->unsignedInteger('id_usuarios_asignados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_usuarios_grupos_trabajos');
    }
};
