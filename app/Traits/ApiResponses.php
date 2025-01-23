<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponses
{
    protected function success(string $message, $data = '', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    protected function paginate(string $message = 'Resources paginated successfully', $data = null, $pagination = null, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => $statusCode,
            'message' => $message,
            'data' => $data,
            'pagination' => $pagination,
        ], $statusCode);
    }

    protected function noContent(): JsonResponse
    {
        return response()->json([
            'status' => 204,
            'message' => 'No Content',
            'data' => null,
        ], 204);
    }

    protected function error($errors = [], int $statusCode, $data = null): JsonResponse
    {
        $formattedErrors = is_array($errors) ? $errors : ['general' => [$errors]];

        $errorArray = array_values($formattedErrors);

        return response()->json([
            'status' => $statusCode,
            'message' => is_string($errors) ? $errors : $errorArray[0][0],
            'errors' => $formattedErrors,
            'data' => $data,
        ], $statusCode);
    }

    protected function notAuthorized(string $message = "Doesn't have permission"): JsonResponse
    {
        return $this->error($message, 401);
    }

    protected function alreadyAuthorized(): JsonResponse
    {
        return $this->error("Already authenticated", 409);
    }

    protected function resourceAlreadyExists(string $message = 'Resource already exists'): JsonResponse
    {
        return $this->error($message, 409);
    }

    protected function resourceNotFound(string $message = 'Resource not found'): JsonResponse
    {
        return $this->error($message, 404);
    }
}
