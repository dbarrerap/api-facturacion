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
        Schema::create('inv_productos', function (Blueprint $table) {
            $table->id('_id');
            $table->unsignedBigInteger('contribuyente_id');
            $table->unsignedBigInteger('proveedor_id');
            $table->string('codigo');
            $table->string('nombre');
            $table->decimal('precio_unitario');
            $table->string('info_adicional')->nullable();
            $table->string('iva_codigo', 2)->default('2')->comment('2: IVA, 3: ICE');
            $table->string('iva_tarifa', 2)->default('2')->comment('0: 0%, 2: 12%, 3: 14%, 6: No Objeto de Impuesto, 7: Exento de IVA');
            $table->boolean('ice')->default(false);
            $table->string('ice_codigo', 2)->nullable();
            $table->string('ice_porcentaje')->nullable();
            $table->decimal('ice_valor')->nullable();
            $table->timestamps();
            $table->softDeletes();
            //
            $table->foreign('contribuyente_id')->references('_id')->on('sis_contribuyentes');
            $table->foreign('proveedor_id')->references('_id')->on('inv_proveedores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inv_productos');
    }
};
