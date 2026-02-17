<?php

declare(strict_types=1);

namespace App\Services\Calculator;

use InvalidArgumentException;

class Lexer
{
    /**
     * @return list<Token>
     */
    public function tokenize(string $input): array
    {
        $tokens = [];
        $length = strlen($input);
        $index = 0;

        while ($index < $length) {
            $char = $input[$index];

            if (ctype_space($char)) {
                $index++;
                continue;
            }

            if (ctype_digit($char) || $char === '.') {
                [$number, $index] = $this->readNumber($input, $index, $length);
                $tokens[] = new Token(TokenType::NUMBER, $number);
                continue;
            }

            if (ctype_alpha($char)) {
                [$identifier, $index] = $this->readIdentifier(
                    $input,
                    $index,
                    $length,
                );
                $tokens[] = new Token(TokenType::IDENTIFIER, $identifier);
                continue;
            }

            $tokenType = match ($char) {
                '+' => TokenType::PLUS,
                '-' => TokenType::MINUS,
                '*' => TokenType::MULTIPLY,
                '/' => TokenType::DIVIDE,
                '^' => TokenType::POWER,
                '(' => TokenType::LPAREN,
                ')' => TokenType::RPAREN,
                default => null,
            };

            if (!$tokenType) {
                throw new InvalidArgumentException(
                    "Unexpected character '{$char}' at position {$index}.",
                );
            }

            $tokens[] = new Token($tokenType, $char);
            $index++;
        }

        $tokens[] = new Token(TokenType::EOF, '');

        return $tokens;
    }

    /**
     * @return array{0:string,1:int}
     */
    private function readNumber(string $input, int $start, int $length): array
    {
        $index = $start;
        $dotCount = 0;

        while ($index < $length) {
            $char = $input[$index];

            if ($char === '.') {
                $dotCount++;

                if ($dotCount > 1) {
                    throw new InvalidArgumentException(
                        "Invalid number near position {$index}.",
                    );
                }

                $index++;
                continue;
            }

            if (!ctype_digit($char)) {
                break;
            }

            $index++;
        }

        $number = substr($input, $start, $index - $start);

        if ($number === '.' || $number === '') {
            throw new InvalidArgumentException(
                "Invalid number near position {$start}.",
            );
        }

        return [$number, $index];
    }

    /**
     * @return array{0:string,1:int}
     */
    private function readIdentifier(
        string $input,
        int $start,
        int $length,
    ): array {
        $index = $start;

        while ($index < $length && ctype_alpha($input[$index])) {
            $index++;
        }

        return [substr($input, $start, $index - $start), $index];
    }
}
