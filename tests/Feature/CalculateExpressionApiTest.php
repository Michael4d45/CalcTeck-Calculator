<?php

declare(strict_types=1);

use App\Models\CalculationHistory;

it('calculates an expression and stores it in history', function () {
    $response = \Pest\Laravel\postJson('/api/calculate', [
        'expression' => '2 + 3 * 4',
    ]);

    $response
        ->assertOk()
        ->assertJsonStructure([
            'data' => ['id', 'expression', 'result', 'created_at'],
        ])
        ->assertJsonPath('data.expression', '2 + 3 * 4')
        ->assertJsonPath('data.result', '14.0000000000');

    \Pest\Laravel\assertDatabaseHas('calculation_histories', [
        'expression' => '2 + 3 * 4',
        'result' => '14.0000000000',
    ]);
});

it('returns validation errors when expression is missing', function () {
    $response = \Pest\Laravel\postJson('/api/calculate', []);

    $response
        ->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors' => ['expression'],
        ]);
});

it('returns parser or evaluation errors in validation format', function () {
    $response = \Pest\Laravel\postJson('/api/calculate', [
        'expression' => '10 / 0',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors' => ['expression'],
        ])
        ->assertJsonPath(
            'errors.expression.0',
            'Division by zero is not allowed.',
        );

    expect(CalculationHistory::query()->count())->toBe(0);
});
