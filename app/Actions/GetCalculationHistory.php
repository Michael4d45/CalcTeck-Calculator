<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\CalculationHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class GetCalculationHistory
{
    public function __invoke(Request $request): JsonResponse
    {
        $histories = CalculationHistory::query()->orderByDesc('id')->get();

        return response()->json([
            'data' => collect($histories)
                ->map(fn($history) => [
                    'id' => $history->id,
                    'expression' => $history->expression,
                    'result' => $history->result,
                    'created_at' => optional($history->created_at)?->toISOString(),
                ])
                ->values()
                ->all(),
            'meta' => [
                'count' => $histories->count(),
            ],
        ]);
    }
}
