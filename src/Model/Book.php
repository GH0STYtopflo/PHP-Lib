<?php

namespace Gh0stytopflo\PhpLib\Model;

use Gh0stytopflo\PhpLib\Persistence\BookHandle;
use Gh0stytopflo\PhpLib\Util\IdGenerator;

class Book extends Model
{
    private int $bookId;
    private string $title;
    private ?string $author;
    private ?int $year;
    private ?int $printing;
    private ?string $genre;
    private ?int $memberId;
    private ?int $borrowDate;
    private ?int $returnDate;

    public function __construct(
        string $title,
        ?string $author = null,
        ?int $year = null,
        ?int $printing = null,
        ?string $genre = null,
        ?int $bookId = null,
        ?int $memberID = null,
        ?int $borrowDate = null,
        ?int $returnDate = null
    ) {
        if (isset($bookId)) {
            $this->bookId = $bookId;
        } else {
            $this->bookId = IdGenerator::generate(fopen(BookHandle::PATH_TO_FILE, 'r'));
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

    public function getBookId(): int
    {
        return $this->bookId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getPrinting(): ?int
    {
        return $this->printing;
    }

    public function setPrinting(?int $printing): self
    {
        $this->printing = $printing;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getMemberId(): ?int
    {
        return $this->memberId;
    }

    public function setMemberId(?int $memberId): self
    {
        $this->memberId = $memberId;

        return $this;
    }

    public function getBorrowDate(): ?int
    {
        return $this->borrowDate;
    }

    public function setBorrowDate(?int $borrowDate): self
    {
        $this->borrowDate = $borrowDate;

        return $this;
    }

    public function getReturnDate(): ?int
    {
        return $this->returnDate;
    }

    public function setReturnDate(?int $returnDate): self
    {
        $this->returnDate = $returnDate;

        return $this;
    }
}
