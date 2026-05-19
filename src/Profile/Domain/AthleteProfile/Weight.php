<?php

declare(strict_types=1);

namespace Src\Profile\Domain\AthleteProfile;

use InvalidArgumentException;

final readonly class Weight
{
    public const float MIN_KILOGRAMS = 10;

    public const float MAX_KILOGRAMS = 500;

    private function __construct(private int $grams)
    {
        if ($this->grams > self::MAX_KILOGRAMS * 1000) {
            throw new InvalidArgumentException('Weight cannot be higher than '.Weight::MAX_KILOGRAMS.' kg');
        }

        if ($this->grams < self::MIN_KILOGRAMS * 1000) {
            throw new InvalidArgumentException('Weight cannot be lower than '.Weight::MIN_KILOGRAMS.' kg');
        }
    }

    public static function fromKilograms(float $kilograms): self
    {
        return self::fromGrams((int) round($kilograms * 1000));
    }

    public static function fromGrams(int $grams): self
    {
        return new self($grams);
    }

    public function kilograms(): float
    {
        return $this->grams / 1000;
    }

    public function grams(): int
    {
        return $this->grams;
    }

    public function equals(self $other): bool
    {
        return $this->grams === $other->grams;
    }
}
