<?php

namespace Gh0stytopflo\PhpLib\Util;

class IdGenerator
{
    public static function generate($file): int
    {
        fseek($file, 0, SEEK_END);
        $pos = ftell($file);

        $line = '';
        while ($pos-- >= -1) {
            fseek($file, $pos);
            $char = fgetc($file);

            if ($pos == -1 || $char == "\n") {
                $line = strrev($line);
                $csvRecord = explode(',', $line);

                if (!empty($csvRecord[0]) && is_numeric($csvRecord[0])) {
                    return $csvRecord[0] + 1;
                }

                $line = '';
            } else {
                $line .= $char;
            }
        }
        return 0;
    }
}
