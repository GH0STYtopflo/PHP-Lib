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
    private int $memberId;
    private int $borrowDate;
    private int $returnDate;

    public function __construct(string $title, string $author, int $year, int $printing, string $genre)
    {
        //TODO: IdGen
        $this->author = $author;
        $this->title = $title;
        $this->year = $year;
        $this->printing = $printing;
        $this->genre = $genre;
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
