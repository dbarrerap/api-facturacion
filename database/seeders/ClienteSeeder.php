<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cliente::insert([
            ['contribuyente_id' => null, 'tipo_documento' => 'CONSUMIDOR FINAL', 'numero_documento' => '9999999999999', 'razon_social' => 'CONSUMIDOR FINAL', 'direccion' => null, 'telefono' => null, 'email' => 'facturacion@dev-studio.tech', 'fecha_nacimiento' => null],
        ]);
    }
}
