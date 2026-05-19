<?php

declare(strict_types=1);

namespace Src\Profile\Domain\AthleteProfile;

use InvalidArgumentException;

final readonly class Height
{
    const int MIN_CENTIMETERS = 10;

    const int MAX_CENTIMETERS = 300;

    private function __construct(private int $centimeters)
    {
        if ($this->centimeters < self::MIN_CENTIMETERS) {
            throw new InvalidArgumentException('Height cannot be lower than '.Height::MIN_CENTIMETERS.' cm');
        }
        if ($this->centimeters > self::MAX_CENTIMETERS) {
            throw new InvalidArgumentException('Height cannot be higher than '.Height::MAX_CENTIMETERS.' cm');
        }
    }

    public static function fromCentimeters(int $centimeters): self
    {
        return new self($centimeters);
    }

    public function value(): int
    {
        return $this->centimeters;
    }

    public function centimeters(): int
    {
        return $this->centimeters;
    }

    public function meters(): float|int
    {
        return $this->centimeters / 100;
    }

    public function equals(self $other): bool
    {
        return $other->centimeters === $this->centimeters;
    }
}
