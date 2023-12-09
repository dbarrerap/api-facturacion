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
        Schema::create('sis_ptoemision', function (Blueprint $table) {
            $table->id('_id');
            $table->unsignedBigInteger('establecimiento_id');
            $table->unsignedBigInteger('contribuyente_id');
            $table->string('numero', 3)->default('100');
            $table->unsignedInteger('factura')->default(1);
            $table->unsignedInteger('nota_credito')->default(1);
            $table->unsignedInteger('nota_debito')->default(1);
            $table->unsignedInteger('guia_remision')->default(1);
            $table->string('estado', 2)->default('A');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('establecimiento_id')->references('_id')->on('sis_establecimientos');
            $table->foreign('contribuyente_id')->references('_id')->on('sis_contribuyentes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sis_ptoemision');
    }
};
