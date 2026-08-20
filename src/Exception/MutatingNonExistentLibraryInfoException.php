<?php

namespace Gh0stytopflo\PhpLib\Exception;

use RuntimeException;

class MutatingNonExistentLibraryInfoException extends RuntimeException
{
    public function __construct(int $code = 0, int $line = 0)
    {
        parent::__construct(
            "Cannot edit non-existent library information.\n" .
            "Try Adding information using '... -A --target=library ...' first.",
            $code);
    }
}