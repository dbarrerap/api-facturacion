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
        Schema::create('sis_empleados', function (Blueprint $table) {
            $table->id('_id');
            $table->unsignedBigInteger('contribuyente_id');
            $table->unsignedBigInteger('establecimiento_id');
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('tipo_documento', 64)->default('CEDULA');
            $table->string('numero_documento', 64);
            $table->string('nacionalidad')->default('Ecuador');  // Nombre de Pais, tomado del Catalogo
            $table->text('direccion');
            $table->date('fecha_nacimiento');
            $table->string('telefono_fijo')->nullable();
            $table->string('telefono_movil')->nullable();
            $table->string('email')->nullable();
            $table->boolean('discapacitado')->default(false);
            $table->string('tipo_discapacidad')->nullable();
            $table->integer('porcentaje_discapacidad')->nullable();
            $table->enum('estado', ['A', 'I'])->default('A');  // A: Activo, I: Inactivo
            $table->timestamps();
            $table->softDeletes();
            //
            $table->foreign('contribuyente_id')->references('_id')->on('sis_contribuyentes');
            $table->foreign('establecimiento_id')->references('_id')->on('sis_establecimientos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sis_empleados');
    }
};
