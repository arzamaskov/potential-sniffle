<?php

declare(strict_types=1);

namespace Src\Profile\Domain\AthleteProfile;

use InvalidArgumentException;

enum Gender: string
{
    case Male = 'male';
    case Female = 'female';

    public static function fromString(string $value): self
    {
        return self::tryFrom($value)
            ?? throw new InvalidArgumentException('Unknown gender');
    }
}
