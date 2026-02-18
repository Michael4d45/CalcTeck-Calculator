<?php

declare(strict_types=1);

it('connects calculator ui to backend history endpoints', function () {
    $page = visit('/');

    $page->assertSee('CalcTeck Calculator')
        ->click('@key-2')
        ->click('@key-plus')
        ->click('@key-3')
        ->click('@key-multiply')
        ->click('@key-4')
        ->click('@calculate-btn')
        ->wait(1)
        ->assertSee('Result: 14.0000000000')
        ->assertSee('2+3*4 = 14.0000000000')
        ->click('@key-9')
        ->click('@key-minus')
        ->click('@key-4')
        ->click('@calculate-btn')
        ->wait(1)
        ->assertSee('9-4 = 5.0000000000')
        ->click('Delete')
        ->wait(1)
        ->assertDontSee('9-4 = 5.0000000000')
        ->assertSee('2+3*4 = 14.0000000000')
        ->click('@clear-history-btn')
        ->wait(1)
        ->assertSee('No calculations yet.');
});
