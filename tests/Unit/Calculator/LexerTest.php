<?php

declare(strict_types=1);

use App\Services\Calculator\Lexer;
use App\Services\Calculator\TokenType;

it('tokenizes numbers operators and identifiers', function () {
    $lexer = new Lexer;

    $tokens = $lexer->tokenize('12 + sqrt(9) * 2^3');

    expect(array_map(fn($token) => $token->type, $tokens))->toBe([
        TokenType::NUMBER,
        TokenType::PLUS,
        TokenType::IDENTIFIER,
        TokenType::LPAREN,
        TokenType::NUMBER,
        TokenType::RPAREN,
        TokenType::MULTIPLY,
        TokenType::NUMBER,
        TokenType::POWER,
        TokenType::NUMBER,
        TokenType::EOF,
    ]);

    expect($tokens[0]->value)
        ->toBe('12')
        ->and($tokens[2]->value)
        ->toBe('sqrt')
        ->and($tokens[7]->value)
        ->toBe('2')
        ->and($tokens[9]->value)
        ->toBe('3');
});

it('throws for invalid character', function () {
    $lexer = new Lexer;

    expect(fn() => $lexer->tokenize('2 & 3'))
        ->toThrow(\InvalidArgumentException::class, 'Unexpected character');
});

it('throws for invalid number format', function () {
    $lexer = new Lexer;

    expect(fn() => $lexer->tokenize('1..2 + 3'))
        ->toThrow(\InvalidArgumentException::class, 'Invalid number');
});
