<?php

namespace Gh0stytopflo\PhpLib\Model;

abstract class Model
{
    abstract public static function mapArrayToInstance(array $csvRecord): self;

    public function getPropertiesArray(): array
    {
        $aarr = (array) $this;
        $newArr = [];

        foreach ($aarr as $k => $v) {
            if (str_contains(strtolower($k), 'id')) {
                $newArr[$k] = $v;
                unset($aarr['$k']);

                $newArr += $aarr;
                break;
            }
        }

        return array_values($newArr);
    }
}
