<?php

namespace Gh0stytopflo\PhpLib\Persistence;

use Gh0stytopflo\PhpLib\Model\Library;

class LibraryHandle
{
    public const PATH_TO_FILE = __DIR__ . '/../../library.json';

    public static function save(Library $library): void
    {
        $file = fopen(self::PATH_TO_FILE, 'w');
        $json = json_encode($library, JSON_PRETTY_PRINT);

        fwrite($file, $json);
    }

    public static function read(): array | false
    {
        $file = fopen(self::PATH_TO_FILE, 'r');

        $json = fread($file, filesize(self::PATH_TO_FILE));
        $aarr = json_decode($json, true);

        return $aarr;
    }

    public static function delete(): void
    {
        $file = fopen(self::PATH_TO_FILE, w);
        fwrite($file, '');
    }
}
