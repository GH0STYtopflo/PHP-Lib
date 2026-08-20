<?php

namespace Gh0stytopflo\PhpLib\Persistence;

use Gh0stytopflo\PhpLib\Exception\LockedFileAccessException;
use Gh0stytopflo\PhpLib\Model\Library;
use Gh0stytopflo\PhpLib\Util\FilePermissionChecker;
use Gh0stytopflo\PhpLib\Util\LockFile;

class LibraryHandle
{
    public const PATH_TO_FILE = __DIR__ . '/../../library.json';

    public static function save(Library $library): void
    {
        if (!FilePermissionChecker::check(self::PATH_TO_FILE)) {
           throw new LockedFileAccessException(self::PATH_TO_FILE, line: __LINE__);
        }

        $file = fopen(self::PATH_TO_FILE, 'w');

        LockFile::lock(self::PATH_TO_FILE);

        $json = json_encode($library, JSON_PRETTY_PRINT);

        fwrite($file, $json);

        LockFile::release(self::PATH_TO_FILE);
    }

    public static function read(): array | false
    {
        $file = fopen(self::PATH_TO_FILE, 'r');

        $json = file_get_contents(self::PATH_TO_FILE);
        $aarr = json_decode($json, true);

        return $aarr;
    }

    public static function delete(): void
    {
        if (!FilePermissionChecker::check(self::PATH_TO_FILE)) {
            throw new LockedFileAccessException(self::PATH_TO_FILE, line: __LINE__);
        }

        $file = fopen(self::PATH_TO_FILE, 'w');

        LockFile::lock(self::PATH_TO_FILE);

        fwrite($file, '');

        LockFile::release(self::PATH_TO_FILE);
    }
}
