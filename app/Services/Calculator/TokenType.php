<?php

declare(strict_types=1);

namespace App\Services\Calculator;

enum TokenType: string
{
    case NUMBER = 'NUMBER';
    case PLUS = '+';
    case MINUS = '-';
    case MULTIPLY = '*';
    case DIVIDE = '/';
    case POWER = '^';
    case LPAREN = '(';
    case RPAREN = ')';
    case IDENTIFIER = 'IDENTIFIER';
    case EOF = 'EOF';
}
