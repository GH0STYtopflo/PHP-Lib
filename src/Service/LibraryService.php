<?php

namespace Gh0stytopflo\PhpLib\Service;

use Gh0stytopflo\PhpLib\Exception\InvalidOpenAndCloseException;
use Gh0stytopflo\PhpLib\Exception\MutatingNonExistentLibraryInfoException;
use Gh0stytopflo\PhpLib\Exception\RequiredPropertyNotProvidedException;
use Gh0stytopflo\PhpLib\Exception\TypeMismatchException;
use Gh0stytopflo\PhpLib\Model\Library;
use Gh0stytopflo\PhpLib\Persistence\LibraryHandle;

class LibraryService
{
    public static function save(array $data): void
    {
        self::saveCheckHook($data);

        $library = new Library(
            $data['name'],
            $data['address'],
            $data['open'],
            $data['close']
        );

        LibraryHandle::save($library);
    }

    private static function saveCheckHook(array $data): void
    {
        if (!isset($data['name'])) {
            throw new RequiredPropertyNotProvidedException('name', line: __LINE__);
        }

        if (!isset($data['address'])) {
            throw new RequiredPropertyNotProvidedException('address', line: __LINE__);
        }

        if (!isset($data['open'])) {
            throw new RequiredPropertyNotProvidedException('open', line: __LINE__);
        }

        if (!isset($data['close'])) {
            throw new RequiredPropertyNotProvidedException('close', line: __LINE__);
        }

        if (!strtotime($data['open']) || !str_contains($data['open'], ':')) {
            throw new TypeMismatchException(
                'open',
                'temporal string (HH:mm)',
                gettype($data['open']) . " (" . ($data['open']) . ")",
                line: __LINE__
            );
        }

        if (!strtotime($data['close']) || !str_contains($data['close'], ':')) {
            throw new TypeMismatchException(
                'close',
                'temporal string [HH:mm]',
                gettype($data['close']) . " [" . ($data['close']) . "]",
                line: __LINE__
            );
        }

        if ((strtotime($data['close']) < strtotime($data['open']))) {
            throw new InvalidOpenAndCloseException($data['open'],$data['close'], line: __LINE__);
        }
    }

    public static function remove(): void
    {
        LibraryHandle::delete();
    }

    public static function read(): Library | null
    {
        $aarr = LibraryHandle::read();

        if ($aarr === null) {
            return null;
        }

        return Library::mapArrayToInstance($aarr);
    }

    public static function edit(array $data): void
    {
        self::editCheckHook($data);
        $library = self::read();

        if ($library === null) {
            throw new MutatingNonExistentLibraryInfoException(line: __LINE__);
        }

        $library->setName(isset($data['name']) ? $data['name'] : $library->getName());
        $library->setAddress(isset($data['address']) ? $data['address'] : $library->getAddress());
        $library->setOpen(isset($data['open']) ? $data['open'] : $library->getOpen());
        $library->setClose(isset($data['close']) ? $data['close'] : $library->getClose());

        LibraryHandle::save($library);
    }

    private static function editCheckHook(array $data): void
    {
        if (isset($data['open']) && (!strtotime($data['open']) || !str_contains($data['open'], ':'))) {
            throw new TypeMismatchException(
                'open',
                'temporal string (HH:mm)',
                gettype($data['open']) . " (" . ($data['open']) . ")",
                line: __LINE__
            );
        }

        if (isset($data['close']) && (!strtotime($data['close']) || !str_contains($data['close'], ':'))) {
            throw new TypeMismatchException(
                'close',
                'temporal string [HH:mm]',
                gettype($data['close']) . " [" . ($data['close']) . "]",
                line: __LINE__
            );
        }

        if ((isset($data['close']) && isset($data['open'])) && (strtotime($data['close']) < strtotime($data['open']))) {
            throw new InvalidOpenAndCloseException($data['open'],$data['close'], line: __LINE__);
        }
    }
}
