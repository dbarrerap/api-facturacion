<?php

namespace App\Console\Commands;

use App\Mail\FacturaMail;
use App\Models\Factura;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class FacElecCorreo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'facelec:correo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envio del XML y RIDE';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $autorizados = Factura::with('cliente')->where('estado', 'A')->get();
        $bar = $this->output->createProgressBar(count($autorizados));

        foreach($autorizados as $autorizado) {
            try {
                if (!isset($autorizado['documento']['cliente']['email'])) {
                    throw new \Exception('Cliente no tiene correo electronico');
                }

                Mail::to($autorizado['documento']['cliente']['email'])
                ->send(new FacturaMail(
                    'emails.factura',
                    $autorizado['fecha_emision'],
                    'DOCUMENTO ELECTRONICO',
                    $autorizado['num_documento'],
                    $autorizado['clave_autorizacion'],
                    $autorizado['documento']['grand_total'],
                    $autorizado['documento']['cliente']['cf2'],
                    $autorizado['documento']['cliente']['name'],
                    [$autorizado['clave_acceso'] . '.xml', $autorizado['clave_acceso'] . '.pdf'],
                    $autorizado['clave_acceso']
                ));

                $datosAct = [
                    'estado' => 'X',
                    'observaciones' => 'Correo enviado a Cliente',
                    'mensaje_error' => null,
                    'identificador' => null,
                ];
                Factura::where(['clave_acceso' => $autorizado['clave_acceso']])->update($datosAct);
                //
            } catch (\Exception $ex) {
                $datosAct = [
                    'identificador' => null,
                    'mensaje_error' => 'EnviarCorreoXML: Error enviando correo (' . $ex->getMessage() . ')',
                ];
                Factura::where(['clave_acceso' => $autorizado['clave_acceso']])->update($datosAct);
            } 
            $bar->advance();
        }
        $bar->finish();

        return Command::SUCCESS;
    }
}
