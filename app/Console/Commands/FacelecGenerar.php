<?php

namespace App\Console\Commands;

use App\Models\Contribuyente;
use App\Models\Factura;
use DOMDocument;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class FacelecGenerar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'facelec:generar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generacion de XML de las facturas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $facturas = Factura::query()
        ->with(['contribuyente', 'establecimiento', 'punto_emision', 'cliente', 'detalles.producto'])
        ->where('estado', 'E')  // Obtener las Facturas con estado Emitido
        ->get();
        $bar = $this->output->createProgressBar($facturas->count());

        foreach($facturas as $factura) {
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
                //
                $datosAct = [
                    'fe_observacion' => 'XML generado correctamente.',
                    'estado' => 'G',
                    'mensaje_error' => null,
                ];
                Factura::where('_id', $factura['_id'])->update($datosAct);
            } catch (\Exception $ex) {
                $datosAct = [
                    'mensaje_error' => 'GenerarXML: Error al generar XML (' . $ex->getMessage() . ')',
                ];
                $this->error($factura['clave_acceso'] . ': ' . $ex->getMessage());
                Factura::where('_id', $factura['_id'])->update($datosAct);
            }
            $bar->advance();
        }
        $bar->finish();

        return Command::SUCCESS;
    }
}
