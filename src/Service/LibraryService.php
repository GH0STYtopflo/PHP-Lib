<?php

namespace Gh0stytopflo\PhpLib\Service;

use Gh0stytopflo\PhpLib\Model\Library;
use Gh0stytopflo\PhpLib\Persistence\LibraryHandle;

class LibraryService
{
    public static function save(
        string $name,
        string $address,
        int $open,
        int $close
    ): void {
        $library = new Library($name, $address, $open, $close);

        LibraryHandle::save($library);
    }

    public static function read(): Library
    {
        $aarr = LibraryHandle::read();

        return Library::mapArrayToInstance($aarr);
    }

    public function editLibrary(
        ?string $name = null,
        ?string $address = null,
        ?int $open = null,
        ?int $close = null
    ): void {
        $library = self::read();

        $library->setName(isset($name) ? $name : $library->getName());
        $library->setAddress(isset($address) ? $address : $library->getAddress());
        $library->setOpen(isset($open) ? $open : $library->getOpen());
        $library->setClose(isset($close) ? $close : $library->getClose());
    }
}
