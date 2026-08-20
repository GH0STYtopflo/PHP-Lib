<?php

namespace Gh0stytopflo\PhpLib\Cli;

use Gh0stytopflo\PhpLib\Exception\MultipleOperationsSpecifiedException;
use Gh0stytopflo\PhpLib\Exception\MultipleTargetsSpecifiedException;
use Gh0stytopflo\PhpLib\Model\Command;
use Gh0stytopflo\PhpLib\Model\Enums\Operation;
use Gh0stytopflo\PhpLib\Model\Enums\Target;

class Parser
{
    private const array OPS = array(
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

        for ($i = 0; $i < count($args); $i++) {
            if (in_array($args[$i], self::OPS) && !isset($operation)) {
                $operation = match ($args[$i]) {
                    '-S' => Operation::SEARCH,
                    '-A' => Operation::ADD,
                    '-D' => Operation::DELETE,
                    '-E' => Operation::EDIT,
                    '-L' => Operation::LIST,
                    '-B' => Operation::BORROW,
                    '-R' => Operation::RETURN,
                    default => $args[$i],
                };
            } elseif (in_array($args[$i], self::OPS) && isset($operation)) {
                throw new MultipleOperationsSpecifiedException(line: __LINE__);
            }

            if (str_contains($args[$i], '--target=')) {
                if (!isset($target)) {
                    $target = match (explode('=', $args[$i])[1]) {
                        'book' => Target::BOOK,
                        'member' => Target::MEMBER,
                        'staff' => Target::STAFF,
                        'library' => Target::LIBRARY,
                        default => $args[$i],
                    };
                } else {
                    throw new MultipleTargetsSpecifiedException(line: __LINE__);
                }
            }

            if (str_contains($args[$i], '--') && !str_contains($args[$i], '--target=')) {
                if (str_contains($args[$i], '=')) {
                    $keyVal = explode('=', $args[$i]);
                    $options[substr($keyVal[0], 2)] = $keyVal[1];
                } else {
                    $flags[] = substr($args[$i], 2);
                }
            }

            if (str_contains($args[$i], '--on')) {
                $expr = $args[++$i];

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
