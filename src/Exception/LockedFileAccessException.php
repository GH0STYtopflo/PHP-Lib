<?php

namespace Gh0stytopflo\PhpLib\Exception;

use RuntimeException;

class LockedFileAccessException extends RuntimeException
{
    public function __construct(string $filename, int $code = 0, int $line = -1)
    {
       $this->code = $code;
       $this->line = $line;

       $this->message = "Cannot mutate locked file [$filename].";
    }
}