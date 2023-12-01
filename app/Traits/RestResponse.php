<?php

namespace App\Trait;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait RestResponse {
    public function success(mixed $data, int $statusCode = Response::HTTP_OK): JsonResponse {
        return new JsonResponse($data, $statusCode);
    }

    public function error(mixed $data, string $message = '', int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse {
        if (!$message) $message = Response::$statusTexts[$statusCode];

        $data = [
            'message' => $message,
            'data' => $data
        ];

        return new JsonResponse($data, $statusCode);
    }

    // 200
    public function okResponse(mixed $data): JsonResponse
    {
        return $this->successResponse($data);
    }

    // 201
    public function createdResponse(mixed $data): JsonResponse
    {
        return $this->successResponse($data, Response::HTTP_CREATED);
    }

    // 204
    public function noContentResponse(): JsonResponse
    {
        return $this->successResponse([], Response::HTTP_NO_CONTENT);
    }

    // 400
    public function badRequestResponse(mixed $data, string $message = ''): JsonResponse
    {
        return $this->errorResponse($data, $message, Response::HTTP_BAD_REQUEST);
    }

    // 404
    public function notFoundResponse(mixed $data, string $message = ''): JsonResponse
    {
        return $this->errorResponse($data, $message, Response::HTTP_NOT_FOUND);
    }

    // 422
    public function unprocessableResponse(mixed $data, string $message = ''): JsonResponse
    {
        return $this->errorResponse($data, $message, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}