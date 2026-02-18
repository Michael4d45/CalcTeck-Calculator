<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\CalculationHistory;
use Symfony\Component\HttpFoundation\Response;

final class DeleteCalculationHistory
{
    public function __invoke(CalculationHistory $calculation): Response
    {
        $calculation->delete();

        return response()->noContent();
    }
}
