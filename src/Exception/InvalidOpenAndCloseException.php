<?php

namespace Gh0stytopflo\PhpLib\Exception;

use RuntimeException;

class InvalidOpenAndCloseException extends RuntimeException
{
    public function __construct(string $open, string $close, int $code = 0, int $line = -1  )
    {
        $this->message = "You cannot close before opening the library. \e[0;31m(close: $close < open: $open)\e[0m";
        $this->code = $code;
        $this->line = $line;
    }
}