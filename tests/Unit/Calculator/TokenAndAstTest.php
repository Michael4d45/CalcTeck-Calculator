<?php

declare(strict_types=1);

use App\Services\Calculator\AST\BinaryOpNode;
use App\Services\Calculator\AST\FunctionCallNode;
use App\Services\Calculator\AST\NumberNode;
use App\Services\Calculator\AST\UnaryOpNode;
use App\Services\Calculator\Token;
use App\Services\Calculator\TokenType;

it('creates token with type and value', function () {
    $token = new Token(TokenType::NUMBER, '123.45');

    expect($token->type)->toBe(TokenType::NUMBER)
        ->and($token->value)->toBe('123.45');
});

it('creates ast nodes with expected shape', function () {
    $left = new NumberNode(2.0);
    $right = new NumberNode(3.0);

    $binary = new BinaryOpNode($left, TokenType::PLUS, $right);
    $unary = new UnaryOpNode(TokenType::MINUS, $left);
    $function = new FunctionCallNode('sqrt', $right);

    expect($binary->left)->toBe($left)
        ->and($binary->operator)->toBe(TokenType::PLUS)
        ->and($binary->right)->toBe($right)
        ->and($unary->operator)->toBe(TokenType::MINUS)
        ->and($unary->operand)->toBe($left)
        ->and($function->name)->toBe('sqrt')
        ->and($function->argument)->toBe($right);
});
