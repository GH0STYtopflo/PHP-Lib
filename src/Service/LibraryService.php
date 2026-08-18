<?php

namespace Gh0stytopflo\PhpLib\Service;

use Gh0stytopflo\PhpLib\Exception\RequiredPropertyNotProvidedException;
use Gh0stytopflo\PhpLib\Exception\TypeMismatchException;
use Gh0stytopflo\PhpLib\Model\Library;
use Gh0stytopflo\PhpLib\Persistence\LibraryHandle;

class LibraryService
{
    public static function save(array $data): void
    {
        if (!isset($data['name'])) {
            throw new RequiredPropertyNotProvidedException('name', line: __LINE__);
        }

        if (!isset($data['address'])) {
            return;

            throw new RequiredPropertyNotProvidedException('address', line: __LINE__);
        }

        if (!isset($data['open'])) {
            return;
            throw new RequiredPropertyNotProvidedException('open', line: __LINE__);
        }

        if (!isset($data['close'])) {
            throw new RequiredPropertyNotProvidedException('close', line: __LINE__);
        }

        if (!is_numeric($data['open'])) {
            throw new TypeMismatchException('open', 'integer', gettype($data['open']), line: __LINE__);
        }

        if (!is_numeric($data['close'])) {
            throw new TypeMismatchException('close', 'integer', gettype($data['close']), line: __LINE__);
        }


        $library = new Library(
            $data['name'],
            $data['address'],
            $data['open'],
            $data['close']
        );

        LibraryHandle::save($library);
    }

    public static function remove(): void
    {
        LibraryHandle::delete();
    }

    public static function read(): Library
    {
        $aarr = LibraryHandle::read();

        return Library::mapArrayToInstance($aarr);
    }

    public static function edit(array $data): void
    {
        $library = self::read();

        $library->setName(isset($data['name']) ? $data['name'] : $library->getName());
        $library->setAddress(isset($data['address']) ? $data['address'] : $library->getAddress());
        $library->setOpen(isset($data['open']) ? strtotime($data['open']) : $library->getOpen());
        $library->setClose(isset($data['close']) ? strtotime($data['close']) : $library->getClose());
    }
}
