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
        Bootstrap::setup();
        array_shift($args);

        try {
            $command = Parser::parse($args);
        } catch (RuntimeException $e) {
            echo $e->getMessage();
            return;
        }
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
                Present::printPrettiy(
                    self::callSearch(
                        $command->getTarget(),
                        $command->getOptions()),
                    $command->getTarget()
                );
                break;
            case Operation::LIST:
                Present::printPrettiy(self::callList($command->getTarget()), $command->getTarget());
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
                    echo "Added new book successfully\n";
                    break;
                case Target::MEMBER:
                    MemberService::add($options);
                    echo "Added new member successfully\n";
                    break;
                case Target::STAFF:
                    StaffService::add($options);
                    echo "Added new staff successfully\n";
                    break;
                case Target::LIBRARY:
                    LibraryService::save($options);
                    echo "Saved library information successfully\n";
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
                    echo "Edited book successfully\n";
                    break;
                case Target::MEMBER:
                    MemberService::edit($data, $options);
                    echo "Edited member successfully\n";
                    break;
                case Target::STAFF:
                    StaffService::edit($data, $options);
                    echo "Edited staff successfully\n";
                    break;
                case Target::LIBRARY:
                    LibraryService::edit($options);
                    echo "Edited library information successfully\n";
                    break;
            }
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    }

    private static function callDelete(Target $target, array $options, array $on): void
    {
        $data = Target::LIBRARY == $target ? null : self::pinpointOn($target, $on);

        if ($target !== Target::LIBRARY & !isset($data)) {
            echo "Found 0 results for target " . $target->name . ". Aborting";
            return;
        }

        try {
            switch ($target) {
                case Target::BOOK:
                    BookService::remove($data);
                    echo "Deleted book successfully\n";
                    break;
                case Target::MEMBER:
                    MemberService::remove($data);
                    echo "Deleted member successfully\n";
                    break;
                case Target::STAFF:
                    StaffService::remove($data);
                    echo "Deleted staff successfully\n";
                    break;
                case Target::LIBRARY:
                    LibraryService::remove();
                    echo "Deleted library information successfully\n";
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
            echo "Borrowed book successfully\n";
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
            echo "Returned book successfully\n";
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
                Target::STAFF => StaffService::search($options),
                Target::LIBRARY => LibraryService::read()
            };
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }

        return $data;
    }

    private static function callList(Target $target): array | Library | null
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

    private static function pinpointOn(Target $target, array $on): Model | null | Library
    {
        $results = self::callSearch($target, $on);

        if ($results instanceof Library || $results === null) {
            return $results;
        }

        if (count($results) == 0) {
            return null;
        } elseif (count($results) == 1) {
            return $results[0];
        } else {
            echo "Found more than 1 results\n";
            Present::printPrettiy($results, $target);
            $selected = $results[self::getSelection(count($results) - 1)];
            return $selected;
        }
    }

    private static function getSelection(int $bound): int
    {
        echo "Choose an option[0 - " . $bound . "]: ";
        $selected = fgets(STDIN);

        if (!is_numeric($selected) || (int) $selected > $bound) {
            echo "please enter a valid number between 0 and $bound\n";
            return self::getSelection($bound);
        }

        return (int) $selected;
    }
}
