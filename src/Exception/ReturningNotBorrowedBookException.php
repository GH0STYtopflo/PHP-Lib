<?php

namespace Gh0stytopflo\PhpLib\Exception;

use RuntimeException;

class ReturningNotBorrowedBookException extends RuntimeException
{
    public function __construct(
        string $message = 'You cannot return this book',
        ?int $line = null
    ) {
        $this->code = 0;
        $this->line = isset($line) ? $line : -1;
        $this->message = $message;
    }
}
