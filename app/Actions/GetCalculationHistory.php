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
        $paginator = CalculationHistory::query()
            ->orderByDesc('id')
            ->cursorPaginate(
                perPage: $request->integer('per_page', 10),
                columns: ['*'],
                cursorName: 'cursor',
                cursor: $request->string('cursor'),
            );

        return response()->json([
            'data' => collect($paginator->items())
                ->map(fn($history) => [
                    'id' => $history->id,
                    'expression' => $history->expression,
                    'result' => $history->result,
                    'created_at' => optional($history->created_at)?->toISOString(),
                ])
                ->values()
                ->all(),
            'meta' => [
                'path' => $paginator->path(),
                'per_page' => $paginator->perPage(),
                'next_cursor' => $paginator->nextCursor()?->encode(),
                'prev_cursor' => $paginator->previousCursor()?->encode(),
            ],
        ]);
    }
}
