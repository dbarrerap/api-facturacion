<?php

namespace Database\Seeders;

use App\Models\Catalogo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CatalogoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Catalogo::insert([
            ['tipo' => 'TIPO DOCUMENTO', 'valor' => 'CEDULA', 'descripcion' => 'CEDULA', 'codigo' => '04', 'estado' => 'A', 'contribuyente_id' => null],
            ['tipo' => 'TIPO DOCUMENTO', 'valor' => 'RUC', 'descripcion' => 'RUC', 'codigo' => '05', 'estado' => 'A', 'contribuyente_id' => null],
            ['tipo' => 'TIPO DOCUMENTO', 'valor' => 'PASAPORTE', 'descripcion' => 'PASAPORTE', 'codigo' => '06', 'estado' => 'A', 'contribuyente_id' => null],
            ['tipo' => 'TIPO DOCUMENTO', 'valor' => 'CONSUMIDOR FINAL', 'descripcion' => 'CONSUMIDOR FINAL', 'codigo' => '07', 'estado' => 'A', 'contribuyente_id' => null],
            ['tipo' => 'IMPUESTO', 'valor' => 'IVA', 'descripcion' => 'Impuesto al Valor Agregado', 'codigo' => '2', 'estado' => 'A', 'contribuyente_id' => null],
            ['tipo' => 'IMPUESTO', 'valor' => 'ICE', 'descripcion' => 'Impuesto a los Consumos Especiales', 'codigo' => '3', 'estado' => 'A', 'contribuyente_id' => null],
            ['tipo' => 'IMPUESTO', 'valor' => 'IRBPNR', 'descripcion' => 'Impuesto Redimible a las Botellas Plásticas No Retornables', 'codigo' => '5', 'estado' => 'A', 'contribuyente_id' => null],
            ['tipo' => 'TARIFA IVA', 'valor' => '0', 'descripcion' => '0%', 'codigo' => '0', 'estado' => 'A', 'contribuyente_id' => null],
            ['tipo' => 'TARIFA IVA', 'valor' => '12', 'descripcion' => '12%', 'codigo' => '2', 'estado' => 'A', 'contribuyente_id' => null],
            ['tipo' => 'TARIFA IVA', 'valor' => '14', 'descripcion' => '14%', 'codigo' => '3', 'estado' => 'A', 'contribuyente_id' => null],
            ['tipo' => 'TARIFA IVA', 'valor' => 'NO OBJETO', 'descripcion' => 'NO OBJETO DE IMPUESTO', 'codigo' => '6', 'estado' => 'A', 'contribuyente_id' => null],
            ['tipo' => 'TARIFA IVA', 'valor' => 'EXENTO IVA', 'descripcion' => 'EXENTO DE IVA', 'codigo' => '7', 'estado' => 'A', 'contribuyente_id' => null],
            ['tipo' => 'FORMA PAGO', 'valor' => '01', 'descripcion' => 'SIN UTILIZACION DEL SISTEMA FINANCIERO', 'codigo' => '01', 'estado' => 'A', 'contribuyente_id' => null],
            ['tipo' => 'FORMA PAGO', 'valor' => '15', 'descripcion' => 'COMPENSACIÓN DE DEUDAS', 'codigo' => '15', 'estado' => 'A', 'contribuyente_id' => null],
            ['tipo' => 'FORMA PAGO', 'valor' => '16', 'descripcion' => 'TARJETA DE DÉBITO', 'codigo' => '16', 'estado' => 'A', 'contribuyente_id' => null],
            ['tipo' => 'FORMA PAGO', 'valor' => '17', 'descripcion' => 'DINERO ELECTRÓNICO', 'codigo' => '17', 'estado' => 'A', 'contribuyente_id' => null],
            ['tipo' => 'FORMA PAGO', 'valor' => '18', 'descripcion' => 'TARJETA PREPAGO', 'codigo' => '18', 'estado' => 'A', 'contribuyente_id' => null],
            ['tipo' => 'FORMA PAGO', 'valor' => '19', 'descripcion' => 'TARJETA DE CRÉDITO', 'codigo' => '19', 'estado' => 'A', 'contribuyente_id' => null],
            ['tipo' => 'FORMA PAGO', 'valor' => '20', 'descripcion' => 'OTROS CON UTILIZACIÓN DEL SISTEMA FINANCIERO', 'codigo' => '20', 'estado' => 'A', 'contribuyente_id' => null],
            ['tipo' => 'FORMA PAGO', 'valor' => '21', 'descripcion' => 'ENDOSO DE TÍTULOS', 'codigo' => '21', 'estado' => 'A', 'contribuyente_id' => null],
        ]);
    }
}
