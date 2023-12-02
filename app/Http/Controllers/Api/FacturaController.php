<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\Api\FacturaService;
use App\Traits\RestResponse;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    use RestResponse;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->filter ?? [];
        $perPage = $request->paginate['perPage'] ?? null;
        $page = $request->paginate['page'] ?? 1;

        $service = new FacturaService();
        $response = $service->getFacturas($filter, $perPage, $page);
        return $this->okResponse($response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $service = new FacturaService();
            $response = $service->setFactura($request->data, $request->cliente, $request->contribuyente);
            //
            return $this->createdResponse($response);
        } catch (\Throwable $th) {
            return $this->badRequestResponse($request->punto_emision, $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $service = new FacturaService();
            $response = $service->getFactura($id);
            //
            return $this->okResponse($response);
        } catch (\Throwable $th) {
            return $this->badRequestResponse($id, $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return $this->forbiddenRequestResponse($request->all(), 'No tiene permitodo modificar una Factura');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->forbiddenRequestResponse($id, 'No tiene permitodo eliminar una Factura');
    }
}
