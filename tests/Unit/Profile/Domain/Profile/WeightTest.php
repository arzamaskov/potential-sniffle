<?php

declare(strict_types=1);

namespace Tests\Unit\Profile\Domain\Profile;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Src\Profile\Domain\AthleteProfile\Weight;

class WeightTest extends TestCase
{
    public function test_it_creates_valid_weight(): void
    {
        $weight = Weight::fromKilograms(75.5);

        $this->assertSame(75.5, $weight->kilograms());
    }

    public function test_it_accepts_minimum_weight(): void
    {
        $weight = Weight::fromKilograms(Weight::MIN_KILOGRAMS);

        $this->assertSame(Weight::MIN_KILOGRAMS, $weight->kilograms());
    }

    public function test_it_accepts_maximum_weight(): void
    {
        $weight = Weight::fromKilograms(Weight::MAX_KILOGRAMS);

        $this->assertSame(Weight::MAX_KILOGRAMS, $weight->kilograms());
    }

    public function test_it_throws_exception_for_too_low_weight(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Weight cannot be lower than '.Weight::MIN_KILOGRAMS.' kg');

        Weight::fromKilograms(Weight::MIN_KILOGRAMS - 0.1);
    }

    public function test_it_throws_exception_for_too_high_weight(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Weight cannot be higher than '.Weight::MAX_KILOGRAMS.' kg');

        Weight::fromKilograms(Weight::MAX_KILOGRAMS + 0.1);
    }

    public function test_it_compares_weights_by_value(): void
    {
        $weight = Weight::fromKilograms(75.5);
        $sameWeight = Weight::fromKilograms(75.5);
        $anotherWeight = Weight::fromKilograms(76.0);

        $this->assertTrue($weight->equals($sameWeight));
        $this->assertFalse($weight->equals($anotherWeight));
    }
}
