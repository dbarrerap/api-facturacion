<?php

namespace App\Http\Services\Api;

use App\Models\Proveedor;

class ProveedorService {
    function getProveedores(array $filter, int|null $perPage, int $page) {
        $proveedores = Proveedor::query();

        return (!is_null($perPage))
            ? $proveedores->paginate($perPage, ['*'], 'page', $page)
            : $proveedores->get();
    }

    function setProveedor(array $data, array $contribuyente) {
        try {
            $proveedor = new Proveedor();
            $proveedor->contribuyente_id = $contribuyente['_id'];
            $proveedor->tipo_documento = $data['tipo_documento'];
            $proveedor->numero_documento = $data['numero_documento'];
            $proveedor->tipo_contribuyente = $data['tipo_contribuyente'] ?? null;
            $proveedor->razon_social = $data['razon_social'];
            $proveedor->representante_legal = $data['representante_legal'];
            $proveedor->direccion = $data['direccion'];
            $proveedor->telefono = $data['telefono'];
            $proveedor->web = $data['web'] ?? null;
            $proveedor->email = $data['email'] ?? null;
            $proveedor->save();
            //
            return $proveedor;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    function getProveedor(string $id) {
        try {
            $proveedor = Proveedor::findOrFail($id);
            //
            return $proveedor;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    function updateProveedor(string $id, array $data) {
        try {
            $proveedor = Proveedor::findOrFail($id);
            $proveedor->representante_legal = $data['representante_legal'];
            $proveedor->direccion = $data['direccion'];
            $proveedor->telefono = $data['telefono'];
            $proveedor->web = $data['web'] ?? null;
            $proveedor->email = $data['email'] ?? null;
            $proveedor->save();
            //
            return $proveedor;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    function deleteProveedor(string $id) {
        try {
            $proveedor = Proveedor::findOrFail($id);
            $proveedor->delete();
            //
            return $proveedor;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }
}