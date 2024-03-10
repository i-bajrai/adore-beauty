<?php

namespace App\Http\Controllers;

use App\Services\PromotionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PromotionController extends Controller
{
    public function index(PromotionService $promotionService, Request $request): JsonResponse
    {
        $this->validateIds($request);

        $promotions = $promotionService->search($request);

        return response()->json($promotions);
    }

    // TODO: this should be extracted to its own class
    // if it became more complicated or is used between multiple controllers.
    protected function validateIds(Request $request): void
    {
        $ids = $request->input('ids');

        if (is_string($ids) && ! preg_match('/^\d+(,\d+)*$/', $ids)) {
            abort(Response::HTTP_BAD_REQUEST, 'Invalid IDs provided.');
        }
    }
}
