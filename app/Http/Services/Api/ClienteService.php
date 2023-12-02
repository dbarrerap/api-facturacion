<?php

namespace App\Http\Services\Api;

use App\Models\Cliente;

class ClienteService {
    function getClientes(array $filter, int|null $perPage, int $page) {
        $clientes = Cliente::query();

        return (!is_null($perPage))
            ? $clientes->paginate($perPage, ['*'], 'page', $page)
            : $clientes->get();
    }

    function setCliente(array $data, array $contribuyente) {
        try {
            $cliente = new Cliente();
            $cliente->contribuyente_id = $contribuyente['_id'];
            $cliente->tipo_documento = $data['tipo_documento'];
            $cliente->numero_documento = $data['numero_documento'];
            $cliente->razon_social = $data['razon_social'];
            $cliente->direccion = $data['direccion'] ?? null;
            $cliente->telefono = $data['telefono'];
            $cliente->email = $data['email'];
            $cliente->fecha_nacimiento = $data['fecha_nacimiento'] ?? null;
            $cliente->save();
            //
            return $cliente;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    function getCliente(string $id) {
        try {
            $cliente = Cliente::findOrFail($id);
            //
            return $cliente;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    function updateCliente(string $id, array $data) {
        try {
            $cliente = Cliente::findOrFail($id);
            $cliente->razon_social = $data['razon_social'];
            $cliente->direccion = $data['direccion'] ?? null;
            $cliente->telefono = $data['telefono'];
            $cliente->email = $data['email'];
            $cliente->fecha_nacimiento = $data['fecha_nacimiento'] ?? null;
            $cliente->save();
            //
            return $cliente;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    function deleteCliente(string $id) {
        try {
            $cliente = Cliente::findOrFail($id);
            $cliente->delete();
            //
            return $cliente;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }
}