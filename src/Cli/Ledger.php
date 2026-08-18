<?php

namespace Gh0stytopflo\PhpLib\Cli;

use Gh0stytopflo\PhpLib\Model\Enums\Operation;
use Gh0stytopflo\PhpLib\Model\Enums\Target;
use Gh0stytopflo\PhpLib\Model\Library;
use Gh0stytopflo\PhpLib\Model\Model;
use Gh0stytopflo\PhpLib\Service\BookService;
use Gh0stytopflo\PhpLib\Service\LibraryService;
use Gh0stytopflo\PhpLib\Service\MemberService;
use Gh0stytopflo\PhpLib\Service\StaffService;
use RuntimeException;

class Ledger
{
    public static function execute(array $args): void
    {
        array_shift($args);

        $command = Parser::parse($args);
        $valMessage = Validate::validate($command);

        if (isset($valMessage)) {
            echo $valMessage . "\n";
            return;
        }

        switch ($command->getOperation()) {
            case Operation::ADD:
                self::callAdd($command->getTarget(), $command->getOptions());
                break;
            case Operation::DELETE:
                self::callDelete($command->getTarget(), $command->getOptions(), $command->getOn());
                break;
            case Operation::SEARCH:
                Present::printPrettiy(self::callSearch($command->getTarget(), $command->getOptions()));
                break;
            case Operation::LIST:
                Present::printPrettiy(self::callList($command->getTarget()));
                break;
            case Operation::EDIT:
                self::callEdit($command->getTarget(), $command->getOptions(), $command->getOn());
                break;
            case Operation::BORROW:
                self::callBorrow($command->getTarget(), $command->getOptions(), $command->getOn());
                break;
            case Operation::RETURN:
                self::callReturn($command->getTarget(), $command->getOptions(), $command->getOn());
                break;
        }
    }

    private static function callAdd(Target $target, array $options)
    {
        try {
            switch ($target) {
                case Target::BOOK:
                    BookService::add($options);
                    break;
                case Target::MEMBER:
                    MemberService::add($options);
                    break;
                case Target::STAFF:
                    StaffService::add($options);
                    break;
                case Target::LIBRARY:
                    LibraryService::save($options);
                    break;
            }
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    }

    private static function callEdit(Target $target, array $options, $on)
    {
        $data = self::pinpointOn($target, $on);

        if (!isset($data) && $target !== Target::LIBRARY) {
            echo "Found 0 results. Aborting";
            return;
        }

        try {
            switch ($target) {
                case Target::BOOK:
                    BookService::editBook($data, $options);
                    break;
                case Target::MEMBER:
                    MemberService::edit($data, $options);
                    break;
                case Target::STAFF:
                    StaffService::edit($data, $options);
                    break;
                case Target::LIBRARY:
                    LibraryService::edit($options);
                    break;
            }
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    }

    private static function callDelete(Target $target, array $options, array $on): void
    {
        $data = self::pinpointOn($target, $on);

        if (!isset($data) && $data !== Target::LIBRARY) {
            echo "Found 0 results for target" . $target->name . ". Aborting";
            return;
        }

        try {
            switch ($target) {
                case Target::BOOK:
                    BookService::remove($data);
                    break;
                case Target::MEMBER:
                    MemberService::remove($data);
                    break;
                case Target::STAFF:
                    StaffService::remove($data);
                    break;
                case Target::LIBRARY:
                    LibraryService::remove();
                    break;
            }
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    }

    private static function callBorrow(Target $target, array $options, array $on): void
    {
        $book = self::pinpointOn($target, $on);
        $member = self::pinpointOn(Target::MEMBER, $on);

        if (!isset($book)) {
            echo "Found 0 results for target BOOK. Aborting";
            return;
        }

        if (!isset($member)) {
            echo "Found 0 results for target MEMBER. Aborting";
            return;
        }

        try {
            BookService::borrowBook($book, $member, $options);
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    }

    private static function callReturn(Target $target, array $options, array $on)
    {
        $book = self::pinpointOn($target, $on);

        if (!isset($book)) {
            echo "Found 0 results for target BOOK. Aborting";
            return;
        }

        try {
            BookService::returnBook($book);
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    }

    private static function callSearch(Target $target, array $options)
    {
        $data = null;

        try {
            $data = match ($target) {
                Target::BOOK => BookService::search($options),
                Target::MEMBER => MemberService::search($options),
                Target::STAFF => StaffService::search($options)
            };
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }

        return $data;
    }

    private static function callList(Target $target): array | Library
    {
        $data = null;

        try {
            $data = match ($target) {
                Target::BOOK => BookService::search([]),
                Target::MEMBER => MemberService::search([]),
                Target::STAFF => StaffService::search([]),
                Target::LIBRARY => LibraryService::read()
            };
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }

        return $data;
    }

    private static function pinpointOn(Target $target, array $on): Model | null
    {
        $results = self::callSearch($target, $on);

        if (count($results) == 0) {
            return null;
        } elseif (count($results) == 1) {
            return $results[0];
        } elseif (count($results) > 1) {
            echo "Found more than 1 results\n";
            Present::printPrettiy($results);

            return $results[self::getSelection(count($results))];
        }
    }

    private static function getSelection(int $bound): int
    {
        echo "Choose an option[0 - " . $bound . "]";
        $selected = fgets(STDIN);

        if (!is_numeric($selected) || (int) $selected > $bound) {
            echo "please enter a valid number between 0 and $bound\n";
            return self::getSelection($bound);
        }

        return (int) $selected;
    }
}
