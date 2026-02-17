<?php

declare(strict_types=1);

namespace App\Services\Calculator;

class Token
{
    public function __construct(
        public TokenType $type,
        public string $value,
    ) {}
}
