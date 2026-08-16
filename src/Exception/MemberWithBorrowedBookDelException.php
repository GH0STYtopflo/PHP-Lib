<?php

namespace Gh0stytopflo\PhpLib\Exception;

use RuntimeException;

class MemberWithBorrowedBookDelException extends RuntimeException
{
    public function __construct(
        string $message = 'You cannot delete this user',
        ?int $line = null
    ) {
        $this->code = 0;
        $this->line = isset($line) ? $line : -1;
        $this->message = $message;
    }
}
