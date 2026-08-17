<?php

namespace Gh0stytopflo\PhpLib\Exception;

use Gh0stytopflo\PhpLib\Model\Book;
use RuntimeException;

class BorrowedBookDeletionException extends RuntimeException
{
    public function __construct(Book $book, ?int $code = null, ?int $line = null)
    {
        $this->line = isset($line) ? $line : -1;
        $this->code = isset($code) ? $code : 0;

        $this->message = "You cannot delete \e[0;33m" . $book->getTitle() . "\e[0m {" .
        $book->getBookId() . '}' .
        ". It is currently borrowed by member \e[0;33m" . (int) $book->getMemberId() . "\e[0m";
    }
}
