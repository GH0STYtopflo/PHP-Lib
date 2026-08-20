<?php

namespace Gh0stytopflo\PhpLib\Exception;

use RuntimeException;

class RequiredPropertyNotProvidedException extends RuntimeException
{
    public function __construct(string $varName, int $code = 0, int $line = -1)
    {
        $this->line = $line;
        $this->code = $code;

        $this->message = "Required property \e[0;31m" . $varName . "\e[0m missing.\nProvide it with --" .
        $varName . "=value";
    }
}
