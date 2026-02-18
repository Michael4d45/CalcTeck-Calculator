<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\CalculationHistory;
use App\Services\Calculator\Calculator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

final class CalculateExpression
{
    public function __construct(
        private readonly Calculator $calculator,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'expression' => ['required', 'string', 'max:500'],
        ]);

        try {
            $history = $this->store($validated['expression']);
        } catch (\InvalidArgumentException $exception) {
            throw ValidationException::withMessages([
                'expression' => [$exception->getMessage()],
            ]);
        }

        return response()->json([
            'data' => [
                'id' => $history->id,
                'expression' => $history->expression,
                'result' => $history->result,
                'created_at' => optional($history->created_at)?->toISOString(),
            ],
        ]);
    }

    private function store(string $expression): CalculationHistory
    {
        $result = $this->calculator->calculate($expression);

        $history = CalculationHistory::query()->create([
            'expression' => $expression,
            'result' => $result,
        ]);

        return $history->fresh();
    }
}
