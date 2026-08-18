<?php

namespace Gh0stytopflo\PhpLib\Util;

class LockFile
{
    public static function lock(string $path): void
    {
        chmod($path, 0444);
    }

    public static function release(string $path): void
    {
        chmod($path, 0644);
    }
}
