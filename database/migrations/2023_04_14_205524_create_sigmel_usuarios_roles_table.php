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
        Schema::create('sigmel_usuarios_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rol_id');
            $table->foreign('rol_id', 'fk_sigmelusuariosroles_rol')->references('id')->on('sigmel_roles')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedInteger('usuario_id');
            $table->foreign('usuario_id', 'fk_sigmelusuarios_usuario')->references('id')->on('users')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('sigmel_usuarios_roles');
    }
};
