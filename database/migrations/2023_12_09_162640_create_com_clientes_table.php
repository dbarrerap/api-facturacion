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
        Schema::create('com_clientes', function (Blueprint $table) {
            $table->id('_id');
            $table->unsignedBigInteger('contribuyente_id');
            $table->string('tipo_documento', 64);
            $table->string('numero_documento', 64);
            $table->string('razon_social');
            $table->text('direccion')->nullable();
            $table->string('telefono');
            $table->string('email');
            $table->date('fecha_nacimiento')->nullable();
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
        Schema::dropIfExists('com_clientes');
    }
};
