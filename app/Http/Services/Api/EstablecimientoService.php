<?php

namespace App\Http\Services\Api;

use App\Models\Establecimiento;

class EstablecimientoService {
    function getEstablecimientos(array $filter, int|null $perPage, int $page) {
        $establecimientos = Establecimiento::query();

        return (!is_null($perPage))
            ? $establecimientos->paginate($perPage, ['*'], 'page', $page)
            : $establecimientos->get();
    }

    function setEstablecimiento(array $data, array $contribuyente) {
        try {
            $establecimiento = new Establecimiento();
            $establecimiento->numero = $data['numero'] ?? '001';
            $establecimiento->nombre_comercial = $data['nombre_comercial'] ?? null;
            $establecimiento->direccion = $data['direccion'];  // Direccion de Establecimiento
            $establecimiento->estado = 'A'; // A: Abierto, C: Cerrado
            $establecimiento->contribuyente_id = $contribuyente['_id'];
            $establecimiento->save();
            //
            return $establecimiento;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    function getEstablecimiento(string $id) {
        try {
            $establecimiento = Establecimiento::findOrFail($id);
            //
            return $establecimiento;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    function updateEstablecimiento(string $id, array $data) {
        try {
            $establecimiento = Establecimiento::findOrFail($id);
            $establecimiento->nombre_comercial = $data['nombre_comercial'];
            $establecimiento->direccion = $data['direccion'];
            $establecimiento->save();
            //
            return $establecimiento;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    function deleteEstablecimiento(string $id) {
        try {
            $establecimiento = Establecimiento::findOrFail($id);
            $establecimiento->delete();
            //
            return null;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }
}