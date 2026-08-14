<?php

namespace Gh0stytopflo\PhpLib\Service;

use Gh0stytopflo\PhpLib\Model\Book;
use Gh0stytopflo\PhpLib\Model\Member;
use Gh0stytopflo\PhpLib\Persistence\BookHandle;

class BookService
{
    public static function add(
        string $title,
        ?string $author,
        ?int $year,
        ?int $printing,
        ?string $genre
    ): void {
        BookHandle::append(new Book($title, $author, $year, $printing, $genre));
    }

    public static function remove(Book $book)
    {
        $csvRecords = BookHandle::readAll();

        foreach ($csvRecords as $i => $record) {
            if ($record[0] == $book->getBookId()) {
                // Prevent deleting a borrowed book
                if (empty($record[6])) {
                    array_splice($csvRecords, $i, length: 1);
                } else {
                    return;
                    // TODO: Throw an exception
                }
                break;
            }
        }
        BookHandle::writeAll($csvRecords);
    }

    public static function search(
        ?int $id = null,
        ?string $title = null,
        ?string $author = null,
        ?int $year = null,
        ?int $printing = null,
        ?string $genre = null
    ): array {
        $records = BookHandle::search($id, $title, $author, $year, $printing, $genre);
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
                if (empty($record[6])) {
                    $record[6] = $member->getMemberId();
                    $record[7] = time();
                    $record[8] = $returnDate;
                } else {
                    return;
                    // Throw an exception
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
                if (empty($record[6])) {
                    $record[6] = '';
                    $record[7] = '';
                    $record[8] = time();
                } else {
                    return;
                    // Throw an exception
                }

                break;
            }
        }

        BookHandle::writeAll($csvRecords);
    }

    public static function editBook(
        Book $book,
        ?string $title = null,
        ?string $author = null,
        ?int $year = null,
        ?int $printing = null,
        ?string $genre = null
    ): void {
        $csvRecords = BookHandle::readAll();

        foreach ($csvRecords as &$record) {
            if ($record[0] == $book->getBookId()) {
                $record[1] = isset($title) ? $title : $record[1];
                $record[2] = isset($author) ? $author : $record[2];
                $record[3] = isset($year) ? $year : $record[3];
                $record[4] = isset($printing) ? $printing : $record[4];
                $record[5] = isset($genre) ? $genre : $record[5];

                break;
            }
        }

        BookHandle::writeAll($csvRecords);
    }
}
