<?php

declare(strict_types=1);

namespace App\Services\Calculator\AST;

use App\Services\Calculator\TokenType;

class BinaryOpNode extends Node
{
    public function __construct(
        public Node $left,
        public TokenType $operator,
        public Node $right,
    ) {}
}
