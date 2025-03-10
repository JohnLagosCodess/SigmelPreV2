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
        Schema::create('sigmel_usuarios_vistas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rol_id');
            $table->foreign('rol_id', 'fk_sigmelusuariosvistas_rol')->references('id')->on('sigmel_roles')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedInteger('vista_id');
            $table->foreign('vista_id', 'fk_sigmelvistas_vista')->references('id')->on('sigmel_vistas')->onDelete('restrict')->onUpdate('restrict');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->enum('tipo', ['principal', 'otro']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sigmel_usuarios_vistas');
    }
};
