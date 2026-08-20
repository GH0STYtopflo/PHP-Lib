<?php

namespace Gh0stytopflo\PhpLib\Exception;

use Gh0stytopflo\PhpLib\Model\Book;
use RuntimeException;

class ReturningNotBorrowedBookException extends RuntimeException
{
    public function __construct(Book $book, int $code = 0, int $line = -1)
    {
        $this->line = $line;
        $this->code = $code;

        $this->message = "You cannot return \e[0;33m" . $book->getTitle() . "\e[0m {id: " .
        $book->getBookId() . '}' . ". It is not borrowed by anyone at the moment";
    }
}
