<?php

namespace Gh0stytopflo\PhpLib\Exception;

use RuntimeException;

class RequiredPropertyNotProvidedException extends RuntimeException
{
    public function __construct(string $varName, ?int $code = null, ?int $line = null)
    {
        $this->line = isset($line) ? $line : -1;
        $this->code = isset($code) ? $code : 0;

        $this->message = "Required property \e[0;31m" . $varName . "\e[0m missing.\nProvide it with --" .
        $varName . "=value";
    }
}
