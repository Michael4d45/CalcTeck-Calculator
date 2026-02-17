<?php

declare(strict_types=1);

namespace App\Services\Calculator\AST;

class NumberNode extends Node
{
    public function __construct(
        public float $value,
    ) {}
}
