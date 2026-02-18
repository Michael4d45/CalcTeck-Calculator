<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\CalculationHistory;
use Illuminate\Http\JsonResponse;

final class ClearCalculationHistory
{
    public function __invoke(): JsonResponse
    {
        $deleted = CalculationHistory::query()->delete();

        return response()->json([
            'message' => 'Calculation history cleared.',
            'deleted' => $deleted,
        ]);
    }
}
