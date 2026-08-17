<?php

namespace Gh0stytopflo\PhpLib\Service;

use Gh0stytopflo\PhpLib\Exception\BorrowedBookDeletionException;
use Gh0stytopflo\PhpLib\Exception\BorrowingBorrowedBookException;
use Gh0stytopflo\PhpLib\Exception\RequiredPropertyNotProvidedException;
use Gh0stytopflo\PhpLib\Exception\ReturningNotBorrowedBookException;
use Gh0stytopflo\PhpLib\Exception\TypeMismatchException;
use Gh0stytopflo\PhpLib\Model\Book;
use Gh0stytopflo\PhpLib\Model\Member;
use Gh0stytopflo\PhpLib\Persistence\BookHandle;

class BookService
{
    public static function add(array $data): void
    {
        if (!isset($data['title'])) {
            throw new RequiredPropertyNotProvidedException(varName: 'title', line: __LINE__);
        }

        if (isset($data['year']) && !is_numeric($data['year'])) {
            throw new TypeMismatchException('year', 'integer', gettype($data['year']), line: __LINE__);
        }

        if (isset($data['printing']) && !is_numeric($data['printing'])) {
            throw new TypeMismatchException('printing', 'integer', gettype($data['printing']), line: __LINE__);
        }

        BookHandle::append(new Book(
            $data['title'],
            isset($data['author']) ? $data['author'] : null,
            isset($data['year']) ? $data['year'] : null,
            isset($data['printing']) ? $data['printing'] : null,
            isset($data['genre']) ? $data['genre'] : null
        ));
    }

    public static function remove(Book $book)
    {
        $csvRecords = BookHandle::readAll();

        foreach ($csvRecords as $i => $record) {
            if ($record[0] == $book->getBookId()) {
                // Prevent deleting a borrowed book
                if (!is_numeric($record[6])) {
                    array_splice($csvRecords, $i, length: 1);
                } else {
                    throw new BorrowedBookDeletionException(book: $book, line: __LINE__);
                }
                break;
            }
        }
        BookHandle::writeAll($csvRecords);
    }

    public static function search(array $data): array
    {
        if (isset($data['year']) && !is_numeric($data['year'])) {
            throw new TypeMismatchException('year', 'numeric', gettype($data['year']), line: __LINE__);
        }

        if (isset($data['printing']) && !is_numeric($data['printing'])) {
            throw new TypeMismatchException('printing', 'numeric', gettype($data['printing']), line: __LINE__);
        }

        if (isset($data['id']) && !is_numeric($data['id'])) {
            throw new TypeMismatchException('id', 'numeric', gettype($data['id']), line: __LINE__);
        }

        $records = BookHandle::search(
            (int) isset($data['id']) ? $data['id'] : null,
            isset($data['title']) ? $data['title'] : null,
            isset($data['author']) ? $data['author'] : null,
            isset($data['year']) ? $data['year'] : null,
            isset($data['printing']) ? $data['printing'] : null,
            isset($data['genre']) ? $data['genre'] : null
        );
        $books = [];

        foreach ($records as $record) {
            $books[] = Book::mapArrayToInstance($record);
        }

        return $books;
    }


    public static function borrowBook(Book $book, Member $member, int $returnDate)
    {
        $csvRecords = BookHandle::readAll();

        foreach ($csvRecords as &$record) {
            if ($record[0] == $book->getBookId()) {
                // Prevent borrowing a book that's already been borrowed
                if (!is_numeric($record[6])) {
                    $record[6] = $member->getMemberId();
                    $record[7] = time();
                    $record[8] = $returnDate;
                } else {
                    throw new BorrowingBorrowedBookException($book, line: __LINE__);
                }

                break;
            }
        }

        BookHandle::writeAll($csvRecords);
    }

    public static function returnBook(Book $book)
    {
        $csvRecords = BookHandle::readAll();

        foreach ($csvRecords as &$record) {
            if ($record[0] == $book->getBookId()) {
                // Prevent returning a book that has not been borrowed
                if (is_numeric($record[6])) {
                    $record[6] = '';
                    $record[7] = '';
                    $record[8] = time();
                } else {
                    throw new ReturningNotBorrowedBookException($book, line: __LINE__);
                }

                break;
            }
        }

        BookHandle::writeAll($csvRecords);
    }

    public static function editBook(
        Book $book,
        array $data
    ): void {
        if (isset($data['year']) && !is_numeric($data['year'])) {
            throw new TypeMismatchException('year', 'numeric', gettype($data['year']), line: __LINE__);
        }

        if (isset($data['printing']) && !is_numeric($data['printing'])) {
            throw new TypeMismatchException('printing', 'numeric', gettype($data['printing']), line: __LINE__);
        }

        $csvRecords = BookHandle::readAll();

        foreach ($csvRecords as &$record) {
            if ($record[0] == $book->getBookId()) {
                $record[1] = isset($data['title']) ? $data['title'] : $record[1];
                $record[2] = isset($data['author']) ? $data['author'] : $record[2];
                $record[3] = isset($data['year']) ? (int) $data['year'] : $record[3];
                $record[4] = isset($data['printing']) ? (int) $data['printing'] : $record[4];
                $record[5] = isset($data['genre']) ? $data['genre'] : $record[5];

                break;
            }
        }

        BookHandle::writeAll($csvRecords);
    }
}
