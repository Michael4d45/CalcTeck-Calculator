<?php

declare(strict_types=1);

namespace App\Services\Calculator;

use App\Services\Calculator\AST\BinaryOpNode;
use App\Services\Calculator\AST\FunctionCallNode;
use App\Services\Calculator\AST\Node;
use App\Services\Calculator\AST\NumberNode;
use App\Services\Calculator\AST\UnaryOpNode;
use InvalidArgumentException;

class Calculator
{
    public function calculate(string $expression): float
    {
        $lexer = new Lexer;
        $tokens = $lexer->tokenize($expression);

        $parser = new Parser($tokens);
        $ast = $parser->parse();

        return $this->evaluate($ast);
    }

    private function evaluate(Node $node): float
    {
        if ($node instanceof NumberNode) {
            return $node->value;
        }

        if ($node instanceof UnaryOpNode) {
            $value = $this->evaluate($node->operand);

            return match ($node->operator) {
                TokenType::PLUS => +$value,
                TokenType::MINUS => -$value,
                default => throw new InvalidArgumentException(
                    'Unsupported unary operator.',
                ),
            };
        }

        if ($node instanceof BinaryOpNode) {
            $left = $this->evaluate($node->left);
            $right = $this->evaluate($node->right);

            return match ($node->operator) {
                TokenType::PLUS => $left + $right,
                TokenType::MINUS => $left - $right,
                TokenType::MULTIPLY => $left * $right,
                TokenType::DIVIDE => $this->safeDivide($left, $right),
                TokenType::POWER => $left ** $right,
                default => throw new InvalidArgumentException(
                    'Unsupported binary operator.',
                ),
            };
        }

        if ($node instanceof FunctionCallNode) {
            $argument = $this->evaluate($node->argument);

            return match (strtolower($node->name)) {
                'sqrt' => $this->safeSqrt($argument),
                default => throw new InvalidArgumentException(
                    "Unsupported function '{$node->name}'.",
                ),
            };
        }

        throw new InvalidArgumentException('Unsupported AST node.');
    }

    private function safeDivide(float $left, float $right): float
    {
        if ($right == 0.0) {
            throw new InvalidArgumentException(
                'Division by zero is not allowed.',
            );
        }

        return $left / $right;
    }

    private function safeSqrt(float $value): float
    {
        if ($value < 0) {
            throw new InvalidArgumentException(
                'Square root of a negative number is not allowed.',
            );
        }

        return sqrt($value);
    }
}
