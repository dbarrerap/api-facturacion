<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\Api\EstablecimientoService;
use App\Traits\RestResponse;
use Illuminate\Http\Request;

class EstablecimientoController extends Controller
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
        //
        $service = new EstablecimientoService();
        $response = $service->getEstablecimientos($filter, $perPage, $page);
        return $this->okResponse($response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $service = new EstablecimientoService();
            $response = $service->setEstablecimiento($request->establecimiento, $request->contribuyente);
            //
            return $this->createdResponse($response);
        } catch (\Throwable $th) {
            return $this->badRequestResponse($request->establecimiento, $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $service = new EstablecimientoService();
            $response = $service->getEstablecimiento($id);
            //
            return $this->createdResponse($response);
        } catch (\Throwable $th) {
            return $this->badRequestResponse($id, $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $service = new EstablecimientoService();
            $response = $service->updateEstablecimiento($id, $request->establecimiento);
            //
            return $this->createdResponse($response);
        } catch (\Throwable $th) {
            return $this->badRequestResponse($id, $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $service = new EstablecimientoService();
            $service->deleteEstablecimiento($id);
            //
            return $this->noContentResponse();
        } catch (\Throwable $th) {
            return $this->badRequestResponse($id, $th->getMessage());
        }
    }
}
