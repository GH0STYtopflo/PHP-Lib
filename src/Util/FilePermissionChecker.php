<?php

namespace Gh0stytopflo\PhpLib\Util;

class FilePermissionChecker
{
    public static function check(string $path): bool {
        $permissions = substr(sprintf("%o", fileperms($path)), -3);

        return (int) $permissions > 400;
    }
}