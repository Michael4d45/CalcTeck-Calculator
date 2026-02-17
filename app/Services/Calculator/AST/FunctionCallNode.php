<?php

declare(strict_types=1);

namespace App\Services\Calculator\AST;

class FunctionCallNode extends Node
{
    public function __construct(
        public string $name,
        public Node $argument,
    ) {}
}
