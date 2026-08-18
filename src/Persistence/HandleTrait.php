<?php

namespace Gh0stytopflo\PhpLib\Persistence;

use Gh0stytopflo\PhpLib\Model\Model;
use Gh0stytopflo\PhpLib\Util\LockFile;

trait HandleTrait
{
    public static function append(Model $data)
    {
        $file = fopen(self::PATH_TO_FILE, 'a+');

        LockFile::lock(self::PATH_TO_FILE);

        fputcsv($file, $data->getPropertiesArray(), ',');

        LockFile::release(self::PATH_TO_FILE);
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

        LockFile::lock(self::PATH_TO_FILE);

        foreach ($records as $record) {
            fputcsv($file, $record, ',');
        }

        LockFile::release(self::PATH_TO_FILE);
    }
}
