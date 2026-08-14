<?php

namespace Gh0stytopflo\PhpLib\Persistence;

use Gh0stytopflo\PhpLib\Model\Model;

class MemberHandle implements Handle
{
    private const PATH_TO_FILE = __DIR__ . '/../../tables/member.csv';

    public static function append(Model $member)
    {
        $file = fopen(self::PATH_TO_FILE, 'a+');

        fputcsv($file, $member->getPropertiesArray(), ',');
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
        ?string $name = null,
        ?string $lname = null,
        ?int $date = null,
        ?int $id = null
    ) {
        $file = fopen(self::PATH_TO_FILE, 'r');
        $records = [];

        while (!feof($file)) {
            $record = fgetcsv($file, 0, ',');

            if (!is_bool($record)) {
                if (
                    (!isset($id) || $id == $record[0])
                    && (!isset($name) || str_contains(strtolower($record[1]), strtolower($name)))
                    && (!isset($lname) || str_contains(strtolower($record[2]), strtolower($lname)))
                    && (!isset($date) || $date == $record[3])
                ) {
                    $records[] = $record;
                }
            }
        }

        return $records;
    }
}
