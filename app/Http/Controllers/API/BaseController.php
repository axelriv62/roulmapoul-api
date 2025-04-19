<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class BaseController extends Controller
{
    public static function throw(Exception $e, string $message)
    {
        Log::error($message);
        throw new Exception($message);
    }

    public function sendResponse(mixed $result, string $message): JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    public function sendError(mixed $errorMessages, string $error, int $code = 400): JsonResponse
    {
        $response = [
            'success' => false,
            'errors' => $errorMessages,
            'message' => $error,
        ];

        return response()->json($response, $code);
    }

}
