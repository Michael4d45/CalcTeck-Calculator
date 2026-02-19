<?php

declare(strict_types=1);

it('performs basic arithmetic calculations', function () {
    $page = visit('/');

    $page
        ->assertSee('CalcTeck Calculator')
        ->click('@key-2')
        ->click('@key-plus')
        ->click('@key-3')
        ->click('@key-multiply')
        ->click('@key-4')
        ->click('@calculate-btn')
        ->wait(1)
        ->assertSee('Result: 14.0000000000')
        ->assertSee('2+3*4 = 14.0000000000')
        ->click('@clear-expression-btn')
        ->click('@key-9')
        ->click('@key-minus')
        ->click('@key-4')
        ->click('@calculate-btn')
        ->wait(1)
        ->assertSee('9-4 = 5.0000000000');
});

it('calculates functions like sqrt from the UI', function () {
    $page = visit('/');

    $page
        ->assertSee('CalcTeck Calculator')
        ->click('@clear-expression-btn')
        ->click('@key-sqrt')
        ->click('@key-9')
        ->click('@key-rparen')
        ->click('@calculate-btn')
        ->wait(1)
        ->assertSee('Result: 3.0000000000')
        ->assertSee('sqrt(9) = 3.0000000000')
        ->click('@clear-history-btn')
        ->wait(1)
        ->assertSee('No calculations yet.');
});

it('manages history items: delete and clear', function () {
    $page = visit('/');

    $page
        ->assertSee('CalcTeck Calculator')
        ->click('@key-2')
        ->click('@key-plus')
        ->click('@key-2')
        ->click('@calculate-btn')
        ->wait(1)
        ->assertSee('2+2 = 4.0000000000')
        ->click('Delete')
        ->wait(1)
        ->assertDontSee('2+2 = 4.0000000000')
        ->click('@clear-expression-btn')
        ->click('@key-3')
        ->click('@key-plus')
        ->click('@key-1')
        ->click('@calculate-btn')
        ->wait(1)
        ->assertSee('3+1 = 4.0000000000')
        ->click('@clear-history-btn')
        ->wait(1)
        ->assertSee('No calculations yet.');
});
