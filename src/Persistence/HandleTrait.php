<?php

namespace Gh0stytopflo\PhpLib\Persistence;

use Gh0stytopflo\PhpLib\Model\Model;

trait HandleTrait
{
    public static function append(Model $data)
    {
        $file = fopen(self::PATH_TO_FILE, 'a+');

        fputcsv($file, $data->getPropertiesArray(), ',');
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
