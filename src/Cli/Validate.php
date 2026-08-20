<?php

namespace Gh0stytopflo\PhpLib\Cli;

use Gh0stytopflo\PhpLib\Model\Command;
use Gh0stytopflo\PhpLib\Model\Enums\Operation;
use Gh0stytopflo\PhpLib\Model\Enums\Target;

class Validate
{
    private const array OP_TARGET_MAP = array(
        'ADD' => [Target::BOOK, Target::MEMBER, Target::STAFF, Target::LIBRARY],
        'DELETE' => [Target::BOOK, Target::MEMBER, Target::STAFF, Target::LIBRARY],
        'LIST' => [Target::BOOK, Target::MEMBER, Target::STAFF, Target::LIBRARY],
        'EDIT' => [Target::BOOK, Target::MEMBER, Target::STAFF, Target::LIBRARY],
        'SEARCH' => [Target::BOOK, Target::MEMBER, Target::STAFF],
        'RETURN' => [Target::BOOK],
        'BORROW' => [Target::BOOK],
    );

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

        if (!in_array($command->getTarget(), self::OP_TARGET_MAP[$command->getOperation()->name])) {
            return "Cannot use target \e[0;31m" . $command->getTarget()->name .
            "\e[0m for operation \e[0;31m" . $command->getOperation()->name . "\e[0m";
        }

        return null;
    }
}
