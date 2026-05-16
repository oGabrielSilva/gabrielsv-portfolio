<?php

namespace App\Http\Controllers;

use App\Services\WorldClockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorldClockController extends Controller
{
    public function __construct(private WorldClockService $service)
    {
    }

    public function search(Request $request): JsonResponse
    {
        $data = $request->validate([
            'query' => 'required|string|min:2|max:80',
        ]);

        $results = $this->service->search($data['query']);

        return response()->json(['results' => $results]);
    }
}
