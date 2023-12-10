<?php

namespace App\Http\Services\Api;

use App\Models\Establecimiento;
use App\Models\Factura;
use App\Models\FacturaDetalle;
use App\Models\PuntoEmision;
use DOMDocument;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            
            $cbn_fecha = \Carbon\Carbon::parse($data['fecha']);
            $clave_acceso = $cbn_fecha->format('dmY');
            $clave_acceso .= Str::padLeft('1', 2, '0');
            $clave_acceso .= $contribuyente['numero_documento'];
            $clave_acceso .= $contribuyente['tipo_ambiente'];
            $clave_acceso .= $establecimiento['numero'];
            $clave_acceso .= $punto_emision['numero'];
            $clave_acceso .= Str::padLeft("$secuencial", 9, '0');
            $clave_acceso .= '12345678';
            $clave_acceso .= '1';
            $clave_acceso .= $this->getMod11Dv($clave_acceso);
    
            $factura = new Factura();
            $factura->contribuyente_id = $contribuyente['_id'];
            $factura->cliente_id = $cliente['_id'];
            $factura->establecimiento = $establecimiento['numero'];
            $factura->punto_emision = $punto_emision['numero'];
            $factura->secuencial = Str::padLeft("$secuencial", 9, '0');
            $factura->fecha = $cbn_fecha->format('d/m/Y');
            $factura->numero_documento = $establecimiento['numero'] . '-' . $punto_emision['numero'] . '-' . Str::padLeft("$secuencial", 9, '0');
            $factura->clave_acceso = $clave_acceso;
            $factura->forma_pago = $data['forma_pago'];
            $factura->base_ice = $data['base_ice'] ?? 0;
            $factura->ice_valor = $data['ice_valor'] ?? 0;
            $factura->base_iva = $data['base_iva'] ?? 0;
            $factura->iva_valor = $data['iva_valor'] ?? 0;
            $factura->base_cero = $data['base_cero'] ?? 0;
            $factura->descuento = $data['descuento'] ?? 0;
            $factura->subtotal = $data['subtotal']; // SUM base_iva, base_cero
            $factura->total = $data['total']; // SUM subtotal, iva_valor
            $factura->observaciones = $data['observaciones'] ?? null;
            $factura->estado = 'E';  // E: Emitida, G: XML generado, F: XML firmado, R: Recibido por el SRI, A: Autorizado por el SRI, X: Enviado por correo al cliente
            $factura->save();

            $punto_emision->increment('factura');
    
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
            $this->generarXML($factura);
            return $factura->load('detalles.producto');
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

    function getMod11Dv($num)
    {
        $digits = str_replace(array('.', ','), array('' . ''), strrev($num));
        if (!ctype_digit($digits)) return false;

        $sum = 0;
        $factor = 2;
        for ($i = 0; $i < strlen($digits); $i++) {
            $sum += substr($digits, $i, 1) * $factor;
            if ($factor == 7) $factor = 2;
            else $factor++;
        }
        $dv = 11 - ($sum % 11);
        if ($dv == 10) return 1;
        if ($dv == 11) return 0;
        return $dv;
    }

    function generarXML(Factura $factura) {
        try {
            $xml = new DOMDocument('1.0', 'UTF-8');
            $xml->xmlStandalone = true;
            $xml->preserveWhiteSpace = false;

            $documento = $xml->createElement('factura');
            $documento = $xml->appendChild($documento);
            $domAttribute = $xml->createAttribute('id');
            $domAttribute->value = 'comprobante';
            $documento->appendChild($domAttribute);
            $domAttribute = $xml->createAttribute('version');
            $domAttribute->value = '1.1.0';
            $documento->appendChild($domAttribute);

            $infoTributaria = $xml->createElement('infoTributaria');
            $infoTributaria = $documento->appendChild($infoTributaria);
            $cbc = $xml->createElement('ambiente', $factura['contribuyente']['tipo_ambiente']);
            $cbc = $infoTributaria->appendChild($cbc);
            $cbc = $xml->createElement('tipoEmision', 1);
            $cbc = $infoTributaria->appendChild($cbc);
            $cbc = $xml->createElement('razonSocial', $factura['contribuyente']['razon_social']);
            $cbc = $infoTributaria->appendChild($cbc);
            $cbc = $xml->createElement('nombreComercial', $factura['establecimiento']['nombre_comercial']);
            $cbc = $infoTributaria->appendChild($cbc);
            $cbc = $xml->createElement('ruc', $factura['contribuyente']['numero_identificacion']);
            $cbc = $infoTributaria->appendChild($cbc);
            $cbc = $xml->createElement('claveAcceso', $factura['clave_acceso']);
            $cbc = $infoTributaria->appendChild($cbc);
            $cbc = $xml->createElement('codDoc', Str::padLeft('1', 2, '0'));  // 01: Factura
            $cbc = $infoTributaria->appendChild($cbc);
            $cbc = $xml->createElement('estab', $factura['establecimiento']['numero']);
            $cbc = $infoTributaria->appendChild($cbc);
            $cbc = $xml->createElement('ptoEmi', $factura['punto_emision']['numero']);
            $cbc = $infoTributaria->appendChild($cbc);
            $cbc = $xml->createElement('secuencial', $factura['secuencial']);
            $cbc = $infoTributaria->appendChild($cbc);
            $cbc = $xml->createElement('dirMatriz', $factura['contribuyente']['direccion']);
            $cbc = $infoTributaria->appendChild($cbc);

            $infoFactura = $xml->createElement('infoFactura');
            $infoFactura = $documento->appendChild($infoFactura);
            $cbc = $xml->createElement('fechaEmision', $factura['fecha']);
            $cbc = $infoFactura->appendChild($cbc);
            $cbc = $xml->createElement('dirEstablecimiento', $factura['establecimiento']['direccion']);
            $cbc = $infoFactura->appendChild($cbc);
            $cbc = $xml->createElement('obligadoContabilidad', $factura['contribuyente']['obligado_contabilidad']);
            $cbc = $infoFactura->appendChild($cbc);

            $tipoIdentificacion = "07";  // Consumidor Final
            if (Str::lower($factura['cliente']['tipo_documento']) == "RUC") {
                $tipoIdentificacion = "04";
            } else if (Str::lower($factura['cliente']['tipo_documento']) == "CEDULA") {
                $tipoIdentificacion = "05";
            } else if (Str::lower($factura['cliente']['tipo_documento']) == "PASAPORTE") {
                $tipoIdentificacion = "06";
            }
            $cbc = $xml->createElement('tipoIdentificacionComprador', $tipoIdentificacion); //ver ficha tecnica SRI tabla 6
            $cbc = $infoFactura->appendChild($cbc);
            $cbc = $xml->createElement('razonSocialComprador', $factura['cliente']['razon_social']);
            $cbc = $infoFactura->appendChild($cbc);
            $cbc = $xml->createElement('identificacionComprador', $factura['cliente']['numero_documento']);
            $cbc = $infoFactura->appendChild($cbc);
            if (isset($factura['cliente']['direccion'])) {
                $cbc = $xml->createElement('direccionComprador', $factura['cliente']['direccion']);
                $cbc = $infoFactura->appendChild($cbc);
            }
            $cbc = $xml->createElement('totalSinImpuestos', $factura['subtotal']);
            $cbc = $infoFactura->appendChild($cbc);
            $cbc = $xml->createElement('totalDescuento', $factura['descuento']);
            $cbc = $infoFactura->appendChild($cbc);
            $totalConImpuestos = $xml->createElement('totalConImpuestos');
            $totalConImpuestos = $infoFactura->appendChild($totalConImpuestos);
            $totalImpuesto = $xml->createElement('totalImpuesto');
            $totalImpuesto = $totalConImpuestos->appendChild($totalImpuesto);
            if ($factura['base_cero'] > 0) {
                $cbc = $xml->createElement('codigo', '2');  //ver ficha tecnica SRI tabla 16 -- 2 IVA, 3 ICE, 5 IRBPNR
                $cbc = $totalImpuesto->appendChild($cbc);
                $cbc = $xml->createElement('codigoPorcentaje', 0); //ver ficha tecnica SRI tabla 17
                $cbc = $totalImpuesto->appendChild($cbc);
                $cbc = $xml->createElement('baseImponible', number_format($factura['base_cero'], 2, '.', ''));
                $cbc = $totalImpuesto->appendChild($cbc);
                $cbc = $xml->createElement('valor', number_format(0, 2, '.', ''));
                $cbc = $totalImpuesto->appendChild($cbc);
            }
            $cbc = $xml->createElement('codigo', '2');  //ver ficha tecnica SRI tabla 16 -- 2 IVA, 3 ICE, 5 IRBPNR
            $cbc = $totalImpuesto->appendChild($cbc);
            $cbc = $xml->createElement('codigoPorcentaje', 2); //ver ficha tecnica SRI tabla 17
            $cbc = $totalImpuesto->appendChild($cbc);
            $cbc = $xml->createElement('baseImponible', number_format($factura['base_iva'], 2, '.', ''));
            $cbc = $totalImpuesto->appendChild($cbc);
            $cbc = $xml->createElement('valor', number_format($factura['iva_valor'], 2, '.', ''));
            $cbc = $totalImpuesto->appendChild($cbc);
            $cbc = $xml->createElement('propina', '0.00');
            $cbc = $infoFactura->appendChild($cbc);
            $cbc = $xml->createElement('importeTotal', number_format($factura['total'], 2, '.', ''));
            $cbc = $infoFactura->appendChild($cbc);
            $cbc = $xml->createElement('moneda', 'DOLAR');
            $cbc = $infoFactura->appendChild($cbc);

            $pagos = $xml->createElement('pagos');
            $pagos = $infoFactura->appendChild($pagos);
            $pago = $xml->createElement('pago');
            $pago = $pagos->appendChild($pago);
            $cbc = $xml->createElement('formaPago', $factura['forma_pago']);  //ver ficha tecnica SRI tabla 24
            $cbc = $pago->appendChild($cbc);
            $cbc = $xml->createElement('total', number_format($factura['total'], 2, '.', ''));
            $cbc = $pago->appendChild($cbc);

            $detalles = $xml->createElement('detalles');
            $detalles = $documento->appendChild($detalles);
            foreach($factura['detalles'] as $facturaDet) {
                $detalle = $xml->createElement('detalle');
                $detalle = $detalles->appendChild($detalle);
                $cbc = $xml->createElement('codigoPrincipal', $facturaDet['producto']['codigo']);
                $cbc = $detalle->appendChild($cbc);
                $cbc = $xml->createElement('descripcion', $facturaDet['producto']['nombre']);
                $cbc = $detalle->appendChild($cbc);
                $cbc = $xml->createElement('cantidad', number_format($facturaDet['cantidad'], 2, '.', ''));
                $cbc = $detalle->appendChild($cbc);
                $cbc = $xml->createElement('precioUnitario', number_format($facturaDet['precio_unitario'], 2, '.', ''));
                $cbc = $detalle->appendChild($cbc);
                $cbc = $xml->createElement('descuento', number_format($facturaDet['descuento'], 2, '.', ''));
                $cbc = $detalle->appendChild($cbc);
                $totalSinImpuesto = $facturaDet['cantidad'] * $facturaDet['precio_unitario'];
                $cbc = $xml->createElement('precioTotalSinImpuesto', number_format($totalSinImpuesto, 2, ".", ""));
                $cbc = $detalle->appendChild($cbc);

                $impuestos = $xml->createElement('impuestos');
                $impuestos = $detalle->appendChild($impuestos);
                $impuesto = $xml->createElement('impuesto');
                $impuesto = $impuestos->appendChild($impuesto);
                $cbc = $xml->createElement('codigo', '2');
                $cbc = $impuesto->appendChild($cbc);
                $codigoImpuesto = ($facturaDet['iva_valor'] > 0) ? '2' : '0';
                $cbc = $xml->createElement('codigoPorcentaje', $codigoImpuesto);
                $cbc = $impuesto->appendChild($cbc);
                $tarifaImpuesto = ($facturaDet['iva_valor'] > 0) ? 12 : 0;
                $cbc = $xml->createElement('tarifa', number_format($tarifaImpuesto, 2, ".", ""));
                $cbc = $impuesto->appendChild($cbc);
                $cbc = $xml->createElement('baseImponible', floatVal($totalSinImpuesto));
                $cbc = $impuesto->appendChild($cbc);
                $cbc = $xml->createElement('valor', number_format($facturaDet['iva_valor'], 2, '.', ''));
                $cbc = $impuesto->appendChild($cbc);
            }
            $xml->formatOutput = true;
            $xml->saveXML();

            $ruta = storage_path('app/comprobantes/generados/');
            $xml->save($ruta . $factura['clave_acceso'] . '.xml');

            $factura->update([
                'fe_observacion' => 'XML generado correctamente.',
                'estado' => 'G',
                'mensaje_error' => null,
            ]);

            $this->firmarXML($factura);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            $factura->update([
                'mensaje_error' => 'GenerarXML: Error al generar XML (' . $ex->getMessage() . ')',
            ]);
        }
    }

    function firmarXML(Factura $factura) {
        try {
            $firmador = storage_path('app/firmador/FirmaElectronica.jar');
            if (!isset($firmador) || empty($firmador)) {
                throw new \Exception('No se ha encontrado el firmador.');
            }

            $nombre_certificado = $factura->contribuyente->certificado;
            if (!isset($nombre_certificado)) {
                throw new \Exception('No se ha configurado el nombre del certificado.');
            }
            $cerificado = storage_path('app/firmador/certificados/' . $nombre_certificado);

            $clave_certificado = $factura->contribuyente->clave_certificado;
            if (!isset($clave_certificado)) {
                throw new \Exception('No se ha configurado la clave del certificado.');
            }

            $input_file = storage_path('app/comprobantes/generados/' . $factura->clave_acceso . ".xml ");
            $output_path = storage_path('app/comprobantes/firmados/');
            $output_file = $factura->clave_acceso . ".xml";

            $command = 'java -jar ' . $firmador . ' "' . $cerificado . '" ' . $clave_certificado . ' ' . $input_file . ' ' . $output_path . ' '. $output_file;
            exec($command, $o);

            foreach($o as $line) {
                Log::info($line);
            }

            if (Str::startsWith($o[count($o) - 1], 'Error')) {
                throw new \Exception($o[count($o) - 1]);
            }

            $factura->update([
                'observaciones' => 'XML firmado correctamente',
                'estado' => 'F',
                'mensaje_error' => null,
            ]);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            $factura->update([
                'mensaje_error' => 'FirmarXML: Error al firmar XML (' . $ex->getMessage() . ')',
            ]);
        }
    }
}