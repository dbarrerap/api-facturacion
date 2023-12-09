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
        Schema::create('inv_proveedores', function (Blueprint $table) {
            $table->id('_id');
            $table->unsignedBigInteger('contribuyente_id');
            $table->string('tipo_documento', 64);
            $table->string('numero_documento', 64);
            $table->string('tipo_contribuyente')->nullable();
            $table->text('razon_social');
            $table->text('representante_legal');
            $table->text('direccion');
            $table->string('telefono', 64);
            $table->string('web')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
            $table->softDeletes();
            //
            $table->foreign('contribuyente_id')->references('_id')->on('sis_contribuyentes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inv_proveedores');
    }
};
