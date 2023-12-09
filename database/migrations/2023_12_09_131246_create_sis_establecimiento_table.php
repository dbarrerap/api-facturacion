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
        Schema::create('sis_establecimientos', function (Blueprint $table) {
            $table->id('_id');
            $table->bigInteger('contribuyente_id');
            $table->string('numero', 3)->default('100');
            $table->text('nombre_comercial')->nullable();
            $table->text('direccion');
            $table->string('estado', 2)->default('A');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('contribuyente_id')->references('_id')->on('sis_contribuyentes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sis_establecimientos');
    }
};
