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
        Schema::create('sis_contribuyentes', function (Blueprint $table) {
            $table->id('_id');
            $table->string('tipo_documento', 64);
            $table->string('numero_documento', 64)->unique();
            $table->text('razon_social');
            $table->text('direccion');
            $table->string('correo');
            $table->string('telefono')->nullable();
            $table->string('movil')->nullable();
            $table->string('contribuyente_especial', 2)->default('NO');
            $table->string('tipo_ambiente', 1)->default(1);
            $table->string('obligado_contabilidad', 2)->default('NO');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sis_contribuyentes');
    }
};
