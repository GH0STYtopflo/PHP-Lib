<?php

namespace Gh0stytopflo\PhpLib\Cli;

use Gh0stytopflo\PhpLib\Model\Command;
use Gh0stytopflo\PhpLib\Model\Enums\Operation;
use Gh0stytopflo\PhpLib\Model\Enums\Target;

class Validate
{
    private const ADD_AND_SEARCHTARGETS = [
        Target::MEMBER,
        Target::BOOK,
        Target::STAFF
    ];

    private const BORROW_AND_RETURN_TARGETS = [
        Target::BOOK
    ];

    public static function validate(Command $command): ?string
    {
        if (null === $command->getOperation()) {
            return "No operations specified";
        }

        if (is_string($command->getOperation())) {
            return "Undefined operation \e[0;31m" . $command->getOperation() . "\e[0m";
        }

        if (null === $command->getTarget()) {
            return "No targets specified";
        }

        if (is_string($command->getTarget())) {
            return "Undefined target \e[0;31m" . $command->getTarget() . "\e[0m";
        }

        if (
            ($command->getOperation() === Operation::ADD || $command->getOperation() === Operation::SEARCH)
            && !in_array($command->getTarget(), self::ADD_AND_SEARCHTARGETS)
        ) {
            return "Cannot use target \e[0;31m" . $command->getTarget()->name .
            "\e[0m for operation \e[0;31m" . $command->getOperation()->name . "\e[0m";
        }

        if (
            ($command->getOperation() === Operation::BORROW || $command->getOperation() === Operation::RETURN)
            && !in_array($command->getTarget(), self::BORROW_AND_RETURN_TARGETS)
        ) {
            return "Cannot use target \e[0;31m" . $command->getTarget()->name .
            "\e[0m for operation \e[0;31m" . $command->getOperation()->name . "\e[0m";
        }

        return null;
    }
}
