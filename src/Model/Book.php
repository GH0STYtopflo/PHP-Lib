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
        return array(0, $this->title, $this->author, $this->year, $this->printing, $this->genre);
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
