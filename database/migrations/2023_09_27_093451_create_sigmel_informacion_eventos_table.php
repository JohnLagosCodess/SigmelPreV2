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
        Schema::connection('sigmel_gestiones')->create('sigmel_informacion_eventos', function (Blueprint $table) {
            $table->increments('Id_Eventos');
            $table->text('Cliente');
            $table->integer('Tipo_cliente');
            $table->integer('Tipo_evento')->nullable();
            $table->string('ID_evento', 10);
            $table->date('F_evento')->nullable();
            $table->date('F_radicacion');
            $table->text('Nombre_usuario');
            $table->date('F_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_informacion_eventos');
    }
};
