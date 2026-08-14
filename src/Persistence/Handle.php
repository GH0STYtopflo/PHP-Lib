<?php

namespace Gh0stytopflo\PhpLib\Persistence;

use Gh0stytopflo\PhpLib\Model\Model;

interface Handle
{
    public static function append(Model $data);

    public static function readAll(): array;

    public static function writeAll(array $records): void;

    public static function findById(int $id): Model | false;
}
