<?php

namespace Gh0stytopflo\PhpLib\Exception;

use Gh0stytopflo\PhpLib\Model\Book;
use RuntimeException;

class ReturningNotBorrowedBookException extends RuntimeException
{
    public function __construct(Book $book, ?int $code = null, ?int $line = null)
    {
        $this->line = isset($line) ? $line : -1;
        $this->code = isset($code) ? $code : 0;

        $this->message = "You cannot return \e[0;33m" . $book->getTitle() . "\e[0m {id: " .
        $book->getBookId() . '}' . ". It is not borrowed by anyone at the moment";
    }
}
