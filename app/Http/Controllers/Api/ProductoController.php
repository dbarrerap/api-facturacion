<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\Api\ProductoService;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->filter ?? [];
        $perPage = $request->paginate['perPage'] ?? null;
        $page = $request->paginate['page'] ?? 1;
        //
        $service = new ProductoService();
        $response = $service->getProductos($filter, $perPage, $page);
        return $this->okResponse($response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $service = new ProductoService();
            $response = $service->setProducto($request->data, $request->contribuyente);
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
            $service = new ProductoService();
            $response = $service->getProducto($id);
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
            $service = new ProductoService();
            $response = $service->updateProducto($id, $request->data);
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
            $service = new ProductoService();
            $response = $service->deleteProduct($id);
            //
            return $this->createdResponse($response);
        } catch (\Throwable $th) {
            return $this->badRequestResponse($id, $th->getMessage());
        }
    }
}
