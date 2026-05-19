<?php

declare(strict_types=1);

namespace Src\Profile\Domain\AthleteProfile;

use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;

final readonly class BirthDate
{
    private function __construct(
        private DateTimeImmutable $date,
    ) {}

    public static function from(string|DateTimeInterface $date): self
    {
        $date = is_string($date)
            ? self::parseString($date)
            : DateTimeImmutable::createFromInterface($date);

        $date = self::normalize($date);

        if ($date > self::today()) {
            throw new InvalidArgumentException('Birth date cannot be in the future');
        }

        return new self($date);
    }

    public function value(): DateTimeImmutable
    {
        return $this->date;
    }

    public function equals(self $other): bool
    {
        return $this->date->format('Y-m-d') === $other->date->format('Y-m-d');
    }

    public function ageAt(DateTimeInterface $date): int
    {
        $date = self::normalize(DateTimeImmutable::createFromInterface($date));

        if ($date < $this->date) {
            throw new InvalidArgumentException('Age calculation date cannot be before birth date');
        }

        return $this->date->diff($date)->y;
    }

    public function toString(): string
    {
        return $this->date->format('Y-m-d');
    }

    private static function parseString(string $date): DateTimeImmutable
    {
        $parsed = DateTimeImmutable::createFromFormat('!Y-m-d', $date);
        $errors = DateTimeImmutable::getLastErrors();

        if (
            $parsed === false
            || $errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)
            || $parsed->format('Y-m-d') !== $date
        ) {
            throw new InvalidArgumentException('Birth date must be in Y-m-d format');
        }

        return $parsed;
    }

    private static function normalize(DateTimeImmutable $date): DateTimeImmutable
    {
        return $date->setTime(0, 0, 0);
    }

    private static function today(): DateTimeImmutable
    {
        return new DateTimeImmutable('today');
    }
}
