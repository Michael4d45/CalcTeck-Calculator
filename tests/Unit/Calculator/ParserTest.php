<?php

declare(strict_types=1);

use App\Services\Calculator\AST\BinaryOpNode;
use App\Services\Calculator\AST\FunctionCallNode;
use App\Services\Calculator\AST\NumberNode;
use App\Services\Calculator\AST\UnaryOpNode;
use App\Services\Calculator\Lexer;
use App\Services\Calculator\Parser;
use App\Services\Calculator\TokenType;

function parse_expression(string $expression)
{
    $lexer = new Lexer;
    $parser = new Parser($lexer->tokenize($expression));

    return $parser->parse();
}

it('parses operator precedence correctly', function () {
    $ast = parse_expression('1 + 2 * 3');

    expect($ast)->toBeInstanceOf(BinaryOpNode::class);

    if (!$ast instanceof BinaryOpNode) {
        return;
    }

    expect($ast->operator)
        ->toBe(TokenType::PLUS)
        ->and($ast->left)
        ->toBeInstanceOf(NumberNode::class)
        ->and($ast->right)
        ->toBeInstanceOf(BinaryOpNode::class);

    if (!$ast->right instanceof BinaryOpNode) {
        return;
    }

    expect($ast->right->operator)->toBe(TokenType::MULTIPLY);
});

it('parses power as right associative', function () {
    $ast = parse_expression('2^3^2');

    expect($ast)->toBeInstanceOf(BinaryOpNode::class);

    if (!$ast instanceof BinaryOpNode) {
        return;
    }

    expect($ast->operator)
        ->toBe(TokenType::POWER)
        ->and($ast->right)
        ->toBeInstanceOf(BinaryOpNode::class);

    if (!$ast->right instanceof BinaryOpNode) {
        return;
    }

    expect($ast->right->operator)->toBe(TokenType::POWER);
});

it('parses unary expression and grouped expression', function () {
    $ast = parse_expression('-(1+2)');

    expect($ast)->toBeInstanceOf(UnaryOpNode::class);

    if (!$ast instanceof UnaryOpNode) {
        return;
    }

    expect($ast->operator)
        ->toBe(TokenType::MINUS)
        ->and($ast->operand)
        ->toBeInstanceOf(BinaryOpNode::class);

    if (!$ast->operand instanceof BinaryOpNode) {
        return;
    }

    expect($ast->operand->operator)->toBe(TokenType::PLUS);
});

it('parses function call expression', function () {
    $ast = parse_expression('sqrt(9)');

    expect($ast)->toBeInstanceOf(FunctionCallNode::class);

    if (!$ast instanceof FunctionCallNode) {
        return;
    }

    expect($ast->name)
        ->toBe('sqrt')
        ->and($ast->argument)
        ->toBeInstanceOf(NumberNode::class);

    if (!$ast->argument instanceof NumberNode) {
        return;
    }

    expect($ast->argument->value)->toBe(9.0);
});

it('throws for trailing tokens', function () {
    expect(fn() => parse_expression('1 2'))
        ->toThrow(
            \InvalidArgumentException::class,
            'Unexpected trailing tokens',
        );
});

it('throws when grouped expression is not closed', function () {
    expect(fn() => parse_expression('(1 + 2'))
        ->toThrow(\InvalidArgumentException::class, 'Expected token');
});
