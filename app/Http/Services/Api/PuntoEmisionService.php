<?php

namespace App\Http\Services\Api;

use App\Models\PuntoEmision;

class PuntoEmisionService {
    function getPuntosEmision(array $filter, int|null $perPage, int $page) {
        $puntos = PuntoEmision::query();

        return (!is_null($perPage))
            ? $puntos->paginate($perPage, ['*'], 'page', $page)
            : $puntos->get();
    }

    function setPuntoEmision(array $data, array $establecimiento, array $contribuyente) {
        try {
            $puntoEmision = new PuntoEmision();
            $puntoEmision->establecimiento_id = $establecimiento['_id'];
            $puntoEmision->contribuyente_id = $contribuyente['_id'];
            $puntoEmision->numero = $data['numero'] ?? '100';
            $puntoEmision->factura = 1;
            $puntoEmision->nota_credito = 1;
            $puntoEmision->nota_debito = 1;
            $puntoEmision->guia_remision = 1;
            $puntoEmision->estado = 'A'; // A: Abierto, C: Cerrado
            $puntoEmision->save();
            //
            return $puntoEmision;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    function getPuntoEmision(string $id) {
        try {
            $puntoEmision = PuntoEmision::findOrFail($id);
            //
            return $puntoEmision;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    function updatePuntoEmision(string $id, $data) {
        try {
            $puntoEmision = PuntoEmision::findOrFail($id);
            $puntoEmision->factura = $data['factura'];
            $puntoEmision->nota_credito = $data['nota_credito'];
            $puntoEmision->nota_debito = $data['nota_debito'];
            $puntoEmision->guia_remision = $data['guia_remision'];
            $puntoEmision->estado = $data['estado'] ?? 'A'; // A: Abierto, C: Cerrado
            $puntoEmision->save();
            //
            return $puntoEmision;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    function deleteEPuntoEmision(string $id) {
        try {
            $puntoEmision = PuntoEmision::findOrFail($id);
            $puntoEmision->delete();
            //
            return $puntoEmision;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }
}