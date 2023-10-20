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
        Schema::connection('sigmel_gestiones')->create('sigmel_numero_orden_eventos', function (Blueprint $table) {
            $table->increments('Id_Orden');
            $table->text('Numero_orden')->nullable();
            $table->string('Proceso', 20)->nullable();
            $table->enum('Estado', ['activo', 'inactivo'])->default('activo')->nullable();
            $table->date('F_orden')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_numero_orden_eventos');
    }
};
