<?php

declare(strict_types=1);

namespace App\Services\Calculator;

use App\Services\Calculator\AST\BinaryOpNode;
use App\Services\Calculator\AST\FunctionCallNode;
use App\Services\Calculator\AST\Node;
use App\Services\Calculator\AST\NumberNode;
use App\Services\Calculator\AST\UnaryOpNode;
use InvalidArgumentException;

class Parser
{
    /**
     * @var list<Token>
     */
    private array $tokens;

    private int $position = 0;

    /**
     * @param list<Token> $tokens
     */
    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
    }

    public function parse(): Node
    {
        $expression = $this->parseExpression();

        if ($this->current()->type !== TokenType::EOF) {
            throw new InvalidArgumentException(
                'Unexpected trailing tokens in expression.',
            );
        }

        return $expression;
    }

    private function parseExpression(int $rightBindingPower = 0): Node
    {
        $token = $this->advance();
        $left = $this->nud($token);

        while (
            $rightBindingPower < $this->leftBindingPower($this->current()->type)
        ) {
            $operator = $this->advance();
            $left = $this->led($operator, $left);
        }

        return $left;
    }

    private function nud(Token $token): Node
    {
        return match ($token->type) {
            TokenType::NUMBER => new NumberNode((float) $token->value),
            TokenType::PLUS, TokenType::MINUS => new UnaryOpNode(
                $token->type,
                $this->parseExpression(40),
            ),
            TokenType::LPAREN => $this->parseGroupedExpression(),
            TokenType::IDENTIFIER => $this->parseFunctionCall($token),
            default => throw new InvalidArgumentException(
                "Unexpected token '{$token->type->name}'.",
            ),
        };
    }

    private function led(Token $operator, Node $left): Node
    {
        $bindingPower = $this->leftBindingPower($operator->type);
        $nextBindingPower = $this->isRightAssociative($operator->type)
            ? $bindingPower - 1
            : $bindingPower;

        $right = $this->parseExpression($nextBindingPower);

        return new BinaryOpNode($left, $operator->type, $right);
    }

    private function parseGroupedExpression(): Node
    {
        $expression = $this->parseExpression();
        $this->expect(TokenType::RPAREN);

        return $expression;
    }

    private function parseFunctionCall(Token $identifier): Node
    {
        $this->expect(TokenType::LPAREN);
        $argument = $this->parseExpression();
        $this->expect(TokenType::RPAREN);

        return new FunctionCallNode($identifier->value, $argument);
    }

    private function leftBindingPower(TokenType $type): int
    {
        return match ($type) {
            TokenType::PLUS, TokenType::MINUS => 10,
            TokenType::MULTIPLY, TokenType::DIVIDE => 20,
            TokenType::POWER => 30,
            default => 0,
        };
    }

    private function isRightAssociative(TokenType $type): bool
    {
        return $type === TokenType::POWER;
    }

    private function expect(TokenType $type): Token
    {
        $token = $this->advance();

        if ($token->type !== $type) {
            throw new InvalidArgumentException(
                "Expected token '{$type->name}', got '{$token->type->name}'.",
            );
        }

        return $token;
    }

    private function current(): Token
    {
        return $this->tokens[$this->position] ?? new Token(TokenType::EOF, '');
    }

    private function advance(): Token
    {
        $current = $this->current();
        $this->position++;

        return $current;
    }
}
