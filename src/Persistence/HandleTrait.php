<?php

namespace Gh0stytopflo\PhpLib\Persistence;

use Gh0stytopflo\PhpLib\Exception\LockedFileAccessException;
use Gh0stytopflo\PhpLib\Model\Model;
use Gh0stytopflo\PhpLib\Util\FilePermissionChecker;
use Gh0stytopflo\PhpLib\Util\LockFile;

trait HandleTrait
{
    public static function append(Model $data)
    {
        if (!FilePermissionChecker::check(self::PATH_TO_FILE)) {
            throw new LockedFileAccessException(self::PATH_TO_FILE, line: __LINE__);
        }

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

            if (is_array($record) && !(count($record) == 1 && $record[0] == null)) {
                $records[] = $record;
            }
        }

        return $records;
    }

    public static function writeAll(array $records): void
    {
        if (!FilePermissionChecker::check(self::PATH_TO_FILE)) {
            throw new LockedFileAccessException(self::PATH_TO_FILE, line: __LINE__);
        }

        $file = fopen(self::PATH_TO_FILE, 'w+');

        LockFile::lock(self::PATH_TO_FILE);

        foreach ($records as $record) {
            fputcsv($file, $record, ',');
        }

        LockFile::release(self::PATH_TO_FILE);
    }
}
