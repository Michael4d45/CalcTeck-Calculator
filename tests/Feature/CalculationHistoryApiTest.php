<?php

declare(strict_types=1);

use App\Models\CalculationHistory;

it('returns history without pagination', function () {
    CalculationHistory::query()->create([
        'expression' => '1+1',
        'result' => 2,
    ]);
    CalculationHistory::query()->create([
        'expression' => '2+2',
        'result' => 4,
    ]);
    CalculationHistory::query()->create([
        'expression' => '3+3',
        'result' => 6,
    ]);
    CalculationHistory::query()->create([
        'expression' => '4+4',
        'result' => 8,
    ]);
    CalculationHistory::query()->create([
        'expression' => '5+5',
        'result' => 10,
    ]);

    $response = \Pest\Laravel\getJson('/api/history');

    $response
        ->assertOk()
        ->assertJsonStructure([
            'data',
            'meta' => ['count'],
        ])
        ->assertJsonCount(5, 'data')
        ->assertJsonPath('data.0.expression', '5+5')
        ->assertJsonPath('data.4.expression', '1+1')
        ->assertJsonPath('meta.count', 5);
});

it('deletes one history item', function () {
    $history = CalculationHistory::query()->create([
        'expression' => '9-4',
        'result' => 5,
    ]);

    \Pest\Laravel\deleteJson('/api/history/' . $history->id)->assertNoContent();

    \Pest\Laravel\assertDatabaseMissing('calculation_histories', [
        'id' => $history->id,
    ]);
});

it('returns 404 when deleting non-existing history item', function () {
    \Pest\Laravel\deleteJson('/api/history/99999')
        ->assertStatus(404)
        ->assertJsonPath(
            'message',
            'No query results for model [App\Models\CalculationHistory] 99999',
        );
});

it('clears all history items', function () {
    CalculationHistory::query()->create([
        'expression' => '5*5',
        'result' => 25,
    ]);
    CalculationHistory::query()->create([
        'expression' => '12/3',
        'result' => 4,
    ]);

    \Pest\Laravel\deleteJson('/api/history')
        ->assertOk()
        ->assertJsonPath('message', 'Calculation history cleared.')
        ->assertJsonPath('deleted', 2);

    expect(CalculationHistory::query()->count())->toBe(0);
});
