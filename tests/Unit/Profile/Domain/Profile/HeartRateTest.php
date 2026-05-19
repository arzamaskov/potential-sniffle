<?php

declare(strict_types=1);

namespace Tests\Unit\Profile\Domain\Profile;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Src\Profile\Domain\AthleteProfile\HeartRate;

class HeartRateTest extends TestCase
{
    public function test_it_creates_valid_heart_rate(): void
    {
        $heartRate = HeartRate::from(168);

        $this->assertSame(168, $heartRate->value());
    }

    public function test_it_accepts_minimum_heart_rate(): void
    {
        $heartRate = HeartRate::from(HeartRate::MIN_BPM);

        $this->assertSame(HeartRate::MIN_BPM, $heartRate->value());
    }

    public function test_it_accepts_maximum_heart_rate(): void
    {
        $heartRate = HeartRate::from(HeartRate::MAX_BPM);

        $this->assertSame(HeartRate::MAX_BPM, $heartRate->value());
    }

    public function test_it_throws_exception_for_too_low_heart_rate(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Heart rate cannot be lower than '.HeartRate::MIN_BPM.' bpm');

        HeartRate::from(HeartRate::MIN_BPM - 1);
    }

    public function test_it_throws_exception_for_too_high_heart_rate(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Heart rate cannot be higher than '.HeartRate::MAX_BPM.' bpm');

        HeartRate::from(HeartRate::MAX_BPM + 1);
    }

    public function test_it_compares_heart_rates_by_value(): void
    {
        $heartRate = HeartRate::from(168);
        $sameHeartRate = HeartRate::from(168);
        $anotherHeartRate = HeartRate::from(170);

        $this->assertTrue($heartRate->equals($sameHeartRate));
        $this->assertFalse($heartRate->equals($anotherHeartRate));
    }
}
