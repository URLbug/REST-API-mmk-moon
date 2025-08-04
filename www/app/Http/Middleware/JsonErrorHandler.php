<?php

namespace App\Http\Middleware;

use Closure;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JsonErrorHandler
{
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $response = $next($request);

        // Если ответ - ошибка 4xx или 5xx
        if ($response->isClientError() || $response->isServerError()) {
            $status = $response->getStatusCode();
            $message = $response->getContent();

            // Если есть исключение, берем его сообщение
            if ($response->exception) {
                $message = $response->exception->getMessage();
            }

            return response()->json([
                'error' => [
                    'code' => $status,
                    'message' => $message,
                ]
            ], $status);
        }

        return $response;
    }
}
