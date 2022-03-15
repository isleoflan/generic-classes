<?php

declare(strict_types=1);

namespace IOL\Generic\v1\BitMasks;

class Scope extends BitMask
{
    public const USER           = 0b0000000001;
    public const CLERK          = 0b0000000010;
    public const WELCOME        = 0b0000000100;
    public const ADMINISTRATION = 0b0000001000;

    public const ADMIN          = 0b1111111111;
}
