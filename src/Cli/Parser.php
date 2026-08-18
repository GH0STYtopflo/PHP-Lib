<?php

namespace Gh0stytopflo\PhpLib\Cli;

use Gh0stytopflo\PhpLib\Model\Command;
use Gh0stytopflo\PhpLib\Model\Enums\Operation;
use Gh0stytopflo\PhpLib\Model\Enums\Target;

class Parser
{
    private const OPS = array(
        '-A',
        '-D',
        '-L',
        '-E',
        '-S',
        '-R',
        '-B',
    );

    public static function parse(array $args): Command
    {
        $operation = null;
        $options = [];
        $flags = [];
        $target = null;
        $on = [];

        foreach ($args as $arg) {
            if (!isset($operation)) {
                $operation = match ($arg) {
                    '-S' => Operation::SEARCH,
                    '-A' => Operation::ADD,
                    '-D' => Operation::DELETE,
                    '-E' => Operation::EDIT,
                    '-L' => Operation::LIST,
                    '-B' => Operation::BORROW,
                    '-R' => Operation::RETURN,
                    default => $arg,
                };
            } else {
                // TODO: Throw multiple ops exception
            }

            if (str_contains($arg, '--target=')) {
                if (!isset($target)) {
                    $target = match (explode('=', $arg)[1]) {
                        'book' => Target::BOOK,
                        'member' => Target::MEMBER,
                        'staff' => Target::STAFF,
                        'library' => Target::LIBRARY,
                        default => $arg,
                    };
                } else {
                    // TODO: THROW multiple targets exception
                }
            }

            if (str_contains($arg, '--') && !str_contains($arg, '--target=') && !str_contains($arg, '--on(')) {
                if (str_contains($arg, '=')) {
                    $keyVal = explode('=', $arg);
                    $options[substr($keyVal[0], 2)] = $keyVal[1];
                } else {
                    $flags[] = substr($arg, 2);
                }
            }

            if (str_contains($arg, '--on[')) {
                $expr = explode('[', $arg)[1];

                if (!str_contains($expr, ']')) {
                    // TODO: throw exception
                }

                foreach (explode(' ', $expr) as $keyVal) {
                    if (str_contains($keyVal, '--') && str_contains($keyVal, '=')) {
                        $key = explode('=', $keyVal)[0];
                        $val = explode('=', $keyVal)[1];

                        $on[substr($key, 2)] = $val;
                    }
                }
            }
        }

        return new Command($operation, $target, $options, $flags, $on);
    }
}
