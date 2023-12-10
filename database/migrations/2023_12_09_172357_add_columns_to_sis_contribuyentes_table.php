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
        Schema::table('sis_contribuyentes', function (Blueprint $table) {
            $table->string('clave_certificado')->after('obligado_contabilidad')->nullable();
            $table->string('certificado')->after('obligado_contabilidad')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sis_contribuyentes', function (Blueprint $table) {
            //
        });
    }
};
