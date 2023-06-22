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
        Schema::connection('sigmel_gestiones')->create('sigmel_listado_estados_procesos', function (Blueprint $table) {
            $table->increments('Id_Estado');
            $table->text('Nombre_estado')->nullable();
            $table->enum('Visible', ['Si','No'])->default('Si')->nullable();            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_listado_estados_procesos');
    }
};
