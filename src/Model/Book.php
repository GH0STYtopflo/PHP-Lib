<?php

namespace Gh0stytopflo\PhpLib\Model;

class Book
{
    private int $bookId;
    private string $title;
    private string $author;
    private int $year;
    private int $printing;
    private string $genre;
    private ?int $memberId;
    private ?int $borrowDate;
    private ?int $returnDate;

    public function __construct(
        string $title,
        string $author,
        int $year,
        int $printing,
        string $genre,
        ?int $bookId = null,
        ?int $memberID = null,
        ?int $borrowDate = null,
        ?int $returnDate = null
    ) {
        if (isset($bookId)) {
            $this->bookId = $bookId;
        }

        $this->author = $author;
        $this->title = $title;
        $this->year = $year;
        $this->printing = $printing;
        $this->genre = $genre;
        $this->memberId = $memberID;
        $this->borrowDate = $borrowDate;
        $this->returnDate = $returnDate;
    }

    public static function mapArrayToInstance(array $csvRecord): self
    {
        // TODO: Throw an exception for invalid input
        return new self(
            bookId: (int) $csvRecord[0],
            title: $csvRecord[1],
            author: $csvRecord[2],
            year: (int) $csvRecord[3],
            printing: (int) $csvRecord[4],
            genre: $csvRecord[5],
            memberID: !empty($csvRecord[6]) ? (int) $csvRecord[6] : null,
            borrowDate: !empty($csvRecord[7]) ? (int) $csvRecord[7] : null,
            returnDate: !empty($csvRecord[8]) ? (int) $csvRecord[8] : null,
        );
    }

    public function getPropertyArray(): array
    {
        return array(
            isset($this->bookId) ?  $this->bookId : null,
            isset($this->title) ? $this->title : null,
            isset($this->author) ? $this->author :  null,
            isset($this->year) ? $this->year : null,
            isset($this->printing) ? $this->printing : null,
            isset($this->genre) ? $this->genre : null,
            isset($this->memberId) ? $this->memberId : null,
            isset($this->borrowDate) ? $this->borrowDate : null,
            isset($this->returnDate) ? $this->returnDate : null
        );
    }

    public function printProperties(): void
    {
        $msg = <<<CODE
            $this->title
              │
              ├── Author: $this->author
              │
              ├── Year: $this->year
              │
              ├── Printing: $this->printing
              │
              └── Genre: $this->genre
            CODE;

        echo $msg;
    }
}
