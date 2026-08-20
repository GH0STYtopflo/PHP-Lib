<?php

namespace Gh0stytopflo\PhpLib\Exception;

use RuntimeException;

class InvalidShiftStartAndEndException extends RuntimeException
{
    public function __construct(string $start, string $end, int $code = 0, int $line = -1)
    {
        $this->message = "Work shift cannot end before starting. \e[0;31m(end: $start < start: $end)\e[0m";
        $this->code = $code;
        $this->line = $line;
    }
}