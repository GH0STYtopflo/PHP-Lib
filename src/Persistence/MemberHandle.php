<?php

namespace Gh0stytopflo\PhpLib\Persistence;

use Gh0stytopflo\PhpLib\Model\Member;
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
        ?int $id = null,
        ?string $name = null,
        ?string $lname = null,
        ?string $phone = null,
        ?string $email = null,
        ?int $date = null
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
                    && (!isset($phone) || $phone == $record[3])
                    && (!isset($email) || $phone == $record[4])
                    && (!isset($date) || $date == $record[5])
                ) {
                    $records[] = $record;
                }
            }
        }

        return $records;
    }

    public static function findById(int $id): Model|false
    {
        $file = fopen(self::PATH_TO_FILE, 'r');

        while (!feof($file)) {
            $csvRecord = fgetcsv($file);

            if ($csvRecord[0] == $id) {
                return Member::mapArrayToInstance($csvRecord);
            }
        }

        return false;
    }
}
