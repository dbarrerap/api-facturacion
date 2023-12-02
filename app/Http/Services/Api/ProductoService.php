<?php

namespace App\Http\Services\Api;

use App\Models\Producto;

class ProductoService {
    function getProductos(array $filter, int|null $perPage, int $page) {
        $productos = Producto::query();

        return (!is_null($perPage))
            ? $productos->paginate($perPage, ['*'], 'page', $page)
            : $productos->get();
    }

    function setProducto(array $data, array $proveedor, array $contribuyente) {
        try {
            $producto = new Producto();
            $producto->contribuyente_id = $contribuyente['_id'];
            $producto->proveedor_id = $proveedor['_id'];
            $producto->codigo = $data['codigo'];
            $producto->nombre = $data['nombre'];
            $producto->precio_unitario = $data['precio_unitario'];
            $producto->info_adicional = $data['info_adicional'] ?? null;
            $producto->iva_codigo = $data['iva_codigo'];
            $producto->iva_tarifa = $data['iva_tarifa'];
            $producto->iva_valor = $data['iva_valor'];
            $producto->ice = $data['ice'] ?? false;
            $producto->ice_codigo = $data['ice_codigo'] ?? null;
            $producto->ice_porcentaje = $data['ice_porcentaje'] ?? null;
            $producto->ice_valor = $data['ice_valor'] ?? null;
            $producto->save();
            //
            return $producto;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    function getProducto(string $id) {
        try {
            $producto = Producto::findOrFail($id);
            //
            return $producto;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    function updateProducto(string $id, array $data) {
        try {
            $producto = Producto::findOrFail($id);
            $producto->codigo = $data['codigo'];
            $producto->nombre = $data['nombre'];
            $producto->precio_unitario = $data['precio_unitario'];
            $producto->info_adicional = $data['info_adicional'] ?? null;
            $producto->iva_codigo = $data['iva_codigo'];
            $producto->iva_tarifa = $data['iva_tarifa'];
            $producto->iva_valor = $data['iva_valor'];
            $producto->ice = $data['ice'] ?? false;
            $producto->ice_codigo = $data['ice_codigo'] ?? null;
            $producto->ice_porcentaje = $data['ice_porcentaje'] ?? null;
            $producto->ice_valor = $data['ice_valor'] ?? null;
            $producto->save();
            //
            return $producto;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    function deleteProduct(string $id) {
        try {
            $producto = Producto::findOrFail($id);
            $producto->delete();
            //
            return $producto;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }
}