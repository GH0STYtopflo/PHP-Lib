<?php

namespace Gh0stytopflo\PhpLib\Model;

use JsonSerializable;

class Library implements JsonSerializable
{
    private string $name;
    private string $address;
    private string $open;
    private string $close;

    public function __construct(string $name, string $address, string $open, string $close)
    {
        $this->name = $name;
        $this->address = $address;
        $this->open = $open;
        $this->close = $close;
    }

    public static function mapArrayToInstance(array $aarr): self
    {
        return new self(
            $aarr['name'],
            $aarr['address'],
            $aarr['open'],
            $aarr['close']
        );
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

    public function getOpen(): string
    {
        return $this->open;
    }

    public function setOpen(string $open): self
    {
        $this->open = $open;

        return $this;
    }

    public function getClose(): string
    {
        return $this->close;
    }

    public function setClose(string $close): self
    {
        $this->close = $close;

        return $this;
    }
}
