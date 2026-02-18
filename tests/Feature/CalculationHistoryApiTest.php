<?php

declare(strict_types=1);

use App\Models\CalculationHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns history using cursor pagination', function () {
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

    $firstPage = \Pest\Laravel\getJson('/api/history?per_page=2');

    $firstPage
        ->assertOk()
        ->assertJsonStructure([
            'data',
            'meta' => ['path', 'per_page', 'next_cursor', 'prev_cursor'],
        ])
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('meta.per_page', 2)
        ->assertJsonPath('data.0.expression', '5+5')
        ->assertJsonPath('data.1.expression', '4+4');

    $nextCursor = $firstPage->json('meta.next_cursor');

    expect($nextCursor)->not->toBeNull();

    $secondPage = \Pest\Laravel\getJson(
        '/api/history?per_page=2&cursor=' . urlencode((string) $nextCursor),
    );

    $secondPage
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.expression', '3+3')
        ->assertJsonPath('data.1.expression', '2+2');

    $thirdCursor = $secondPage->json('meta.next_cursor');

    expect($thirdCursor)->not->toBeNull();

    $thirdPage = \Pest\Laravel\getJson(
        '/api/history?per_page=2&cursor=' . urlencode((string) $thirdCursor),
    );

    $thirdPage
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.expression', '1+1')
        ->assertJsonPath('meta.next_cursor', null);
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
        ->assertJsonPath('message', 'No query results for model [App\Models\CalculationHistory] 99999');
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
