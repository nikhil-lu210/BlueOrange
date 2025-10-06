<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

trait ApiResponseTrait
{
    /**
     * Return a success JSON response.
     */
    protected function successResponse(
        mixed $data = null,
        string $message = 'Success',
        int $statusCode = 200
    ): JsonResponse {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Return an error JSON response.
     */
    protected function errorResponse(
        string $message = 'Error',
        mixed $data = null,
        int $statusCode = 400
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Return a validation error JSON response.
     */
    protected function validationErrorResponse(
        array $errors,
        string $message = 'Validation failed'
    ): JsonResponse {
        return $this->errorResponse($message, $errors, 422);
    }

    /**
     * Return a not found JSON response.
     */
    protected function notFoundResponse(
        string $message = 'Resource not found'
    ): JsonResponse {
        return $this->errorResponse($message, null, 404);
    }

    /**
     * Return an unauthorized JSON response.
     */
    protected function unauthorizedResponse(
        string $message = 'Unauthorized'
    ): JsonResponse {
        return $this->errorResponse($message, null, 401);
    }

    /**
     * Return a forbidden JSON response.
     */
    protected function forbiddenResponse(
        string $message = 'Forbidden'
    ): JsonResponse {
        return $this->errorResponse($message, null, 403);
    }

    /**
     * Return a server error JSON response.
     */
    protected function serverErrorResponse(
        string $message = 'Internal server error'
    ): JsonResponse {
        return $this->errorResponse($message, null, 500);
    }

    /**
     * Return a resource response with proper formatting.
     */
    protected function resourceResponse(
        JsonResource $resource,
        string $message = 'Success'
    ): JsonResponse {
        return $this->successResponse($resource, $message);
    }

    /**
     * Return a collection response with proper formatting.
     */
    protected function collectionResponse(
        mixed $collection,
        string $message = 'Success'
    ): JsonResponse {
        return $this->successResponse($collection, $message);
    }
}
