<?php

namespace Gh0stytopflo\PhpLib\Model;

interface Model
{
    public static function mapArrayToInstance(array $csvRecord): self;

    public function getPropertiesArray(): array;

    public function printProperties(): void;
}
