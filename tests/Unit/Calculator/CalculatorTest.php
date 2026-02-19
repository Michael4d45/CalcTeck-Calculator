<?php

declare(strict_types=1);

use App\Services\Calculator\Calculator;

it('evaluates arithmetic with precedence', function () {
    $calculator = new Calculator;

    expect($calculator->calculate('2 + 3 * 4'))->toBe(14.0);
});

it('evaluates power as right associative', function () {
    $calculator = new Calculator;

    expect($calculator->calculate('2^3^2'))->toBe(512.0);
});

it('evaluates complex expression with sqrt and power', function () {
    $calculator = new Calculator;

    expect($calculator->calculate('sqrt((((9*9)/12)+(13-4))*2)^2'))->toBe(31.5);
});

it('throws on division by zero', function () {
    $calculator = new Calculator;

    expect(fn() => $calculator->calculate('10 / 0'))
        ->toThrow(\InvalidArgumentException::class, 'Division by zero');
});

it('throws on sqrt of negative number', function () {
    $calculator = new Calculator;

    expect(fn() => $calculator->calculate('sqrt(-1)'))
        ->toThrow(
            \InvalidArgumentException::class,
            'Square root of a negative number',
        );
});

it('throws on unsupported function', function () {
    $calculator = new Calculator;

    expect(fn() => $calculator->calculate('fake(1)'))
        ->toThrow(\InvalidArgumentException::class, 'Unsupported function');
});
