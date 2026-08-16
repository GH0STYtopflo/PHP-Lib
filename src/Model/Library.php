<?php

namespace Gh0stytopflo\PhpLib\Model;

use JsonSerializable;

class Library implements JsonSerializable
{
    private string $name;
    private string $address;
    private int $open;
    private int $close;

    public function __construct(string $name, string $address, int $open, int $close)
    {
        $this->name = $name;
        $this->address = $address;
        $this->open = $open;
        $this->close = $close;
    }

    public static function mapArrayToInstance(array $aarr): self
    {
        return new self($aarr['name'], $aarr['address'], $aarr['open'], $aarr['close']);
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getOpen(): int
    {
        return $this->open;
    }

    public function setOpen(int $open): self
    {
        $this->open = $open;

        return $this;
    }

    public function getClose(): int
    {
        return $this->close;
    }

    public function setClose(int $close): self
    {
        $this->close = $close;

        return $this;
    }
}
