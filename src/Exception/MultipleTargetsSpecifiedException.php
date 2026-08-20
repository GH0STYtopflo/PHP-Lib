<?php

namespace Gh0stytopflo\PhpLib\Exception;

use RuntimeException;

class MultipleTargetsSpecifiedException extends RuntimeException
{
    public function __construct(int $line = -1, int $code = 0)
    {
        $this->line = $line;
        $this->code = $code;

        $this->message = "You have specified more than 1 targets";
    }
}
