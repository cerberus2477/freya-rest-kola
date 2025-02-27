<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseController extends Controller
{
    protected function jsonResponse(int $status, string $message, $data): JsonResponse
    {
        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data instanceof LengthAwarePaginator ? $data->items() : $data,
        ];

        if ($data instanceof LengthAwarePaginator) {
            $response['pagination'] = [
                'total' => $data->total(),
                'page' => $data->currentPage(),
                'pageSize' => $data->perPage(),
                'totalPages' => $data->lastPage(),
            ];
        }

        return response()->json($response);
    }
}