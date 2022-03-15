<?php

declare(strict_types=1);

namespace IOL\Generic\v1\Enums;

use IOL\Generic\v1\Exceptions\InvalidValueException;
use JsonSerializable;
use ReflectionClass;

class Enum implements JsonSerializable
{
    protected string|int $value;

    /**
     * @throws InvalidValueException
     */
    public function __construct(string|int $value)
    {
        $this->value = $value;
        $reflection = new ReflectionClass(static::class);

        if (!in_array($this->value, array_values($reflection->getConstants()), true)) {
            throw new InvalidValueException('The value "' . $this->value . '" is not allowed', 5418);
        }
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
