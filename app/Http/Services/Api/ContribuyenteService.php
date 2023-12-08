<?php

namespace App\Http\Services\Api;

use App\Models\Contribuyente;

class ContribuyenteService {
    function getContribuyentes(array $filter, int|null $perPage, int $page) {
        $contribuyentes = Contribuyente::query()
        ->where('usuario', $filter['user'])
        ->when(
            isset($filter['razon_social']),
            fn ($q) => $q->where('razon_social', 'like', '%' . $filter['razon_social'] . '%')
        );

        return (!is_null($perPage))
            ? $contribuyentes->paginate($perPage, ['*'], 'page', $page)
            : $contribuyentes->get();
    }

    function setContribuyente(array $data, array $user) {
        try {
            $contribuyente = new Contribuyente();
            $contribuyente->tipo_documento = $data['tipo_documento'] ?? 'RUC';  // RUC, CEDULA, PASAPORTE
            $contribuyente->numero_documento = $data['numero_documento'];
            $contribuyente->razon_social = $data['razon_social'];  // Direccion de Matriz
            $contribuyente->direccion = $data['direccion'];
            $contribuyente->correo = $data['correo'];
            $contribuyente->telefono = $data['telefono'] ?? null;
            $contribuyente->movil = $data['movil'] ?? null;
            $contribuyente->contribuyente_especial = $data['contribuyente_especial'] ?? 'NO';
            $contribuyente->tipo_ambiente = $data['tipo_ambiente'] ?? '1'; // 1: Pruebas, 2: Produccion
            $contribuyente->obligado_contabilidad = $data['obligado_contabilidad'] ?? 'NO';
            $contribuyente->usuario = $user['_id'];
            $contribuyente->save();
            //
            return $contribuyente;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    function getContribuyente(string $id) {
        try {
            $contribuyente = Contribuyente::findOrFail($id);
            //
            return $contribuyente;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    function updateContribuyente(string $id, array $data) {
        try {
            $contribuyente = Contribuyente::findOrFail($id);
            $contribuyente->razon_social = $data['razon_social'];
            $contribuyente->correo = $data['correo'];
            $contribuyente->telefono = $data['telefono'] ?? null;
            $contribuyente->movil = $data['movil'] ?? null;
            $contribuyente->tipo_ambiente = $data['tipo_ambiente'] ?? '1'; // 1: Pruebas, 2: Produccion
            $contribuyente->save();
            //
            return $contribuyente;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    function deleteContribuyente(string $id) {
        try {
            $contribuyente = Contribuyente::findOrFail($id);
            $contribuyente->delete();
            //
            return null;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }
}