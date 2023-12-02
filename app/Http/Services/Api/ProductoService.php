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

    function setProducto(array $data, array $contribuyente) {
        try {
            $producto = new Producto();
            $producto->codigo = $data['codigo'];
            $producto->nombre = $data['nombre'];
            $producto->precio_unitario = $data['precio_unitario'];
            $producto->info_adicional = $data['info_adicional'] ?? null;
            $producto->tarifa_iva = $data['tarifa_iva'];
            $producto->ice = $data['ice'] ?? false;
            $producto->codigo_ice = $data['codigo_ice'] ?? null;
            $producto->contribuyente_id = $contribuyente['_id'];
            //
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
            $producto->tarifa_iva = $data['tarifa_iva'];
            $producto->ice = $data['ice'] ?? false;
            $producto->codigo_ice = $data['codigo_ice'] ?? null;
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