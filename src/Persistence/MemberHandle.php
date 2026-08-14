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
}
