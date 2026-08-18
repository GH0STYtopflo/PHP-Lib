<?php

namespace Gh0stytopflo\PhpLib\Exception;

use RuntimeException;

class TypeMismatchException extends RuntimeException
{
    public function __construct(string $varName, string $expected, string $actual, ?int $code = null, ?int $line = null)
    {
        $this->line = isset($line) ? $line : -1;
        $this->code = isset($code) ? $code : 0;

        $this->message = "Expected \e[0;32m" . $expected . "\e[0m type " . "For property '$varName' got \e[0;31m$actual\e[0m";
    }
}
