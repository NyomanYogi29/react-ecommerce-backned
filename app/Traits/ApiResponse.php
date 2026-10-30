<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    public function ok(mixed $data = null, string $message = 'Success', int $code = 200): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function error(string $message = 'Error', int $code = 400, mixed $errors = null): JsonResponse {
        $result = [
            'success' => false,
            'message' => $message
        ];

        if ($errors !== null) {
            $result['errors'] = $errors;
        }

        return response()->json($result, $code);
    }

    // Spesific APIResponse trait
    public function created(mixed $data = null, string $message = 'Created'): JsonResponse
    {
        return $this->ok($data, $message, 201);
    }

    public function deleted(mixed $data = null, string $message = 'Deleted'): JsonResponse
    {
        return $this->ok($data, $message, 204);
    }

    public function notFound(string $message = 'Content not found.', int $code = 404): JsonResponse {
        return $this->error($message, $code);
    }

    public function forbidden(string $message = 'You are forbid to enter this content.', int $code = 403): JsonResponse {
        return $this->error($message, $code);
    }

    public function paginated(LengthAwarePaginator $paginator, string $resourceClass, string $messgae = 'SUccess'): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $messgae,
            'data' => $resourceClass::collection($paginator->items()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage()
            ]
        ]);
    }
}
