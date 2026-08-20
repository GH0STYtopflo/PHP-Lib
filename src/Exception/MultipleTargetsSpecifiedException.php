<?php

namespace Gh0stytopflo\PhpLib\Exception;

use RuntimeException;

class MultipleTargetsSpecifiedException extends RuntimeException
{
    public function __construct(?int $line = null, ?int $code = null)
    {
        $this->line = isset($line) ? $line : -1;
        $this->code = isset($code) ? $code : 0;

        $this->message = "You have specified more than 1 targets";
    }
}
