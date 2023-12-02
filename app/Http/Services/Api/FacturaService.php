<?php

namespace App\Http\Services\Api;

use App\Models\Establecimiento;
use App\Models\Factura;
use App\Models\FacturaDetalle;
use App\Models\PuntoEmision;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FacturaService {
    function getFacturas(array $filter, int|null $perPage, int $page) {
        $facturas = Factura::query()
        ->with('detalles');

        return (!is_null($perPage))
            ? $facturas->paginate($perPage, ['*'], 'page', $page)
            : $facturas->get();
    }

    function setFactura(array $data, array $cliente, array $contribuyente) {
        DB::beginTransaction();
        try {
            $establecimiento = Establecimiento::findOrFail($data['establecimiento']);
            $punto_emision = PuntoEmision::findOrFail($data['punto_emision']);
            $secuencial = $punto_emision->factura;
    
            $factura = new Factura();
            $factura->contribuyente_id = $contribuyente['_id'];
            $factura->cliente_id = $cliente['_id'];
            $factura->establecimiento = $establecimiento['numero'];
            $factura->punto_emision = $punto_emision['numero'];
            $factura->secuencial = Str::padLeft("$secuencial", 9, '0');
            $factura->fecha = $data['fecha'];
            $factura->numero_documento = $establecimiento['numero'] . '-' . $punto_emision['numero'] . '-' . Str::padLeft("$secuencial", 9, '0');
            $factura->forma_pago = $data['forma_pago'];
            $factura->base_iva = $data['base_iva'];
            $factura->base_cero = $data['base_cero'];
            $factura->iva_valor = $data['iva_valor'];
            $factura->subtotal = $data['subtotal']; // SUM base_iva, base_cero
            $factura->total = $data['total']; // SUM subtotal, iva_valor
            $factura->observaciones = $data['observaciones'] ?? null;
            $factura->save();
    
            foreach($data['detalles'] as $detalle) {
                $facturaDet = new FacturaDetalle();
                $facturaDet->producto_id = $detalle['_id'];
                $facturaDet->producto_codigo = $detalle['codigo'];
                $facturaDet->cantidad = $detalle['cantidad'];
                $facturaDet->precio_unitario = $detalle['precio_unitario'];
                $facturaDet->descuento = $detalle['descuento'];
                $facturaDet->ice = $detalle['ice'] ?? false; // True, False
                $facturaDet->ice_codigo = $detalle['ice_codigo'] ?? null;
                $facturaDet->ice_porcentaje = $detalle['ice_porcentaje'] ?? null;
                $facturaDet->ice_base = $detalle['ice_base'];
                $facturaDet->ice_valor = $detalle['ice_valor'] ?? null;
                $facturaDet->iva_codigo = $detalle['iva_codigo'];
                $facturaDet->iva_base = $detalle['iva_base'];
                $facturaDet->iva_valor = $detalle['iva_valor'];
                $facturaDet->precio_total = $detalle['precio_total']; // cantidad * PU - descuento + ice_valor + iva_valor
                $factura->detalles()->save($facturaDet);
            }
            DB::commit();
            //
            return $factura->load('detalles');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception($th->getMessage());
        }
    }

    function getFactura(string $id) {
        try {
            $factura = Factura::query()
            ->with([
                'cliente', 
                'detalles'
            ])
            ->where('_id', $id)
            ->first();
            //
            return ($factura) ? $factura : [];
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }
}