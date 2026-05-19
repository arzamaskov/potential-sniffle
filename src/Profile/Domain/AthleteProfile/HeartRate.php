<?php

declare(strict_types=1);

namespace Src\Profile\Domain\AthleteProfile;

use InvalidArgumentException;

final readonly class HeartRate
{
    public const int MIN_BPM = 10;

    public const int MAX_BPM = 300;

    private function __construct(private int $bpm)
    {
        if ($bpm < HeartRate::MIN_BPM) {
            throw new InvalidArgumentException('Heart rate cannot be lower than '.HeartRate::MIN_BPM.' bpm');
        }

        if ($bpm > HeartRate::MAX_BPM) {
            throw new InvalidArgumentException('Heart rate cannot be higher than '.HeartRate::MAX_BPM.' bpm');
        }
    }

    public static function from(int $bpm): self
    {
        return new self($bpm);
    }

    public function value(): int
    {
        return $this->bpm;
    }

    public function equals(self $other): bool
    {
        return $this->bpm === $other->bpm;
    }

    public function greaterThan(self $other): bool
    {
        return $this->bpm > $other->bpm;
    }
}
