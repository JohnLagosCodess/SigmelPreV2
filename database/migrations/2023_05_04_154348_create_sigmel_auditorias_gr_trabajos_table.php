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
        Schema::connection('sigmel_auditorias')->create('sigmel_auditorias_gr_trabajos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_usuario_sesion');
            $table->text('nombre_usuario_sesion');
            $table->text('email_usuario_sesion');
            $table->text('acccion_realizada');
            $table->timestamp('fecha_registro_accion');
        });
    }



    /*  
         text
        fecha_registro_accion timestamp 
        
    */


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_auditorias_gr_trabajos');
    }
};
