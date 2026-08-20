<?php

namespace Gh0stytopflo\PhpLib\Exception;

use Gh0stytopflo\PhpLib\Model\Book;
use RuntimeException;

class BorrowedBookDeletionException extends RuntimeException
{
    public function __construct(Book $book, int $code = 0, int $line = -1)
    {
        $this->line = $line;
        $this->code = $code;

        $this->message = "You cannot delete \e[0;33m" . $book->getTitle() . "\e[0m {id: " .
        $book->getBookId() . '}' .
        ". It is currently borrowed by member \e[0;33m" . (int) $book->getMemberId() . "\e[0m";
    }
}
