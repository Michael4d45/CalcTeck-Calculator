<?php

declare(strict_types=1);

namespace App\Services\Calculator\AST;

use App\Services\Calculator\TokenType;

class UnaryOpNode extends Node
{
    public function __construct(
        public TokenType $operator,
        public Node $operand,
    ) {}
}
