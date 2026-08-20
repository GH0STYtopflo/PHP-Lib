<?php

namespace Gh0stytopflo\PhpLib\Cli;

use Gh0stytopflo\PhpLib\Persistence\BookHandle;
use Gh0stytopflo\PhpLib\Persistence\LibraryHandle;
use Gh0stytopflo\PhpLib\Persistence\MemberHandle;
use Gh0stytopflo\PhpLib\Persistence\StaffHandle;

class Bootstrap
{
    public static function setup(): void
    {
        date_default_timezone_set('Asia/Tehran');

        if (!is_dir(__DIR__ . '/../../tables/')) {
            mkdir(__DIR__ . '/../../tables/');
        }

        if (!file_exists(LibraryHandle::PATH_TO_FILE)) {
            touch(LibraryHandle::PATH_TO_FILE);
        }

        if (!file_exists(MemberHandle::PATH_TO_FILE)) {
            touch(MemberHandle::PATH_TO_FILE);
        }

        if (!file_exists(StaffHandle::PATH_TO_FILE)) {
            touch(StaffHandle::PATH_TO_FILE);
        }

        if (!file_exists(BookHandle::PATH_TO_FILE)) {
            touch(BookHandle::PATH_TO_FILE);
        }
    }
}
