<?php

namespace Gh0stytopflo\PhpLib\Cli;

use Gh0stytopflo\PhpLib\Model\Library;

class Present
{
    private const string SPACES = '   ';

    public static function printPrettiy(null|array|Library $data): void
    {
        if (!isset($data)) {
            echo 'No results found';
            return;
        }

        if (gettype($data) == 'Library') {
            echo json_encode($data, JSON_PRETTY_PRINT);
            return;
        }

        if (count($data) < 1) {
            echo 'No results found';
            return;
        }

        for ($i = 0; $i < count($data); $i++) {
            $str = "#$i:\n\n";
            $str .=
                self::SPACES . '[' . self::SPACES . array_last(explode('\\', $data[$i]::class)) . self::SPACES . "]\n";

            foreach ((array) $data[$i] as $key => $value) {
                $property = array_last(explode('\\', $key));
                $property = str_contains($property, '*') ? substr($property, 2) : $property;

                $str .= self::SPACES . $property . ' = ' . "$value\n";
            }

            echo $str . "--------------------------------------------\n";
        }
    }
}
