<?php

namespace Gh0stytopflo\PhpLib\Persistence;

use Gh0stytopflo\PhpLib\Model\Book;
use Gh0stytopflo\PhpLib\Model\Model;

class BookHandle implements Handle
{
    public const PATH_TO_FILE = __DIR__ . '/../../tables/book.csv';

    public static function append(Model $book)
    {
        $file = fopen(self::PATH_TO_FILE, 'a+');

        fputcsv($file, $book->getPropertiesArray(), ',');
    }

    public static function readAll(): array
    {
        $file = fopen(self::PATH_TO_FILE, 'r');
        $records = [];

        while (!feof($file)) {
            $record = fgetcsv($file, 0, ',');

            if (!is_bool($record)) {
                $records[] = $record;
            }
        }

        return $records;
    }

    public static function writeAll(array $records): void
    {
        $file = fopen(self::PATH_TO_FILE, 'w+');

        foreach ($records as $record) {
            fputcsv($file, $record, ',');
        }
    }

    public static function search(
        ?int $id = null,
        ?string $title = null,
        ?string $author = null,
        ?int $year = null,
        ?int $printing = null,
        ?string $genre = null
    ): array {
        $file = fopen(self::PATH_TO_FILE, 'r');
        $records = [];

        while (!feof($file)) {
            $record = fgetcsv($file, 0, ',');

            if (!is_bool($record)) {
                if (
                    (!isset($id) || $id == $record[0])
                    && (!isset($title) || str_contains(strtolower($record[1]), strtolower($title)))
                    && (!isset($author) || str_contains(strtolower($record[2]), strtolower($author)))
                    && (!isset($year) || $year == $record[3])
                    && (!isset($printing) || $year == $record[4])
                    && (!isset($genre) || str_contains(strtolower($record[5]), strtolower($genre)))
                ) {
                    $records[] = $record;
                }
            }
        }

        return $records;
    }

    public static function findById(int $id): Model | false
    {
        $file = fopen(self::PATH_TO_FILE, 'r');

        while (!feof($file)) {
            $csvRecord = fgetcsv($file);

            if ($csvRecord[0] == $id) {
                return Book::mapArrayToInstance($csvRecord);
            }
        }

        return false;
    }
}
