<?php

namespace Gh0stytopflo\PhpLib\Model;

use Gh0stytopflo\PhpLib\Model\Enums\Operation;
use Gh0stytopflo\PhpLib\Model\Enums\Target;

class Command
{
    private null|string|Operation $operation;
    private null|string|Target $target;
    private array $options;
    private array $flags;
    private array $on;

    public function __construct(
        null|string|Operation $operation,
        null|string|Target $target,
        array $options,
        array $flags,
        array $on,
    ) {
        $this->operation = $operation;
        $this->target = $target;
        $this->options = $options;
        $this->flags = $flags;
        $this->on = $on;
    }

    public function getOperation(): null|string|Operation
    {
        return $this->operation;
    }

    public function getTarget(): null|string|Target
    {
        return $this->target;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getFlags(): array
    {
        return $this->flags;
    }

    public function getOn(): array
    {
        return $this->on;
    }
}
