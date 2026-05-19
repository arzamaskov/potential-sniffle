<?php

declare(strict_types=1);

namespace Tests\Unit\Profile\Domain\Profile;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Src\Profile\Domain\AthleteProfile\Height;

class HeightTest extends TestCase
{
    public function test_it_creates_valid_height(): void
    {
        $height = Height::fromCentimeters(180);

        $this->assertSame(180, $height->centimeters());
    }

    public function test_it_accepts_minimum_height(): void
    {
        $height = Height::fromCentimeters(Height::MIN_CENTIMETERS);

        $this->assertSame(Height::MIN_CENTIMETERS, $height->centimeters());
    }

    public function test_it_accepts_maximum_height(): void
    {
        $height = Height::fromCentimeters(Height::MAX_CENTIMETERS);

        $this->assertSame(Height::MAX_CENTIMETERS, $height->centimeters());
    }

    public function test_it_throws_exception_for_too_low_height(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Height cannot be lower than '.Height::MIN_CENTIMETERS.' cm');

        Height::fromCentimeters(Height::MIN_CENTIMETERS - 1);
    }

    public function test_it_throws_exception_for_too_high_height(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Height cannot be higher than '.Height::MAX_CENTIMETERS.' cm');

        Height::fromCentimeters(Height::MAX_CENTIMETERS + 1);
    }

    public function test_it_compares_heights_by_value(): void
    {
        $height = Height::fromCentimeters(180);
        $sameHeight = Height::fromCentimeters(180);
        $anotherHeight = Height::fromCentimeters(181);

        $this->assertTrue($height->equals($sameHeight));
        $this->assertFalse($height->equals($anotherHeight));
    }
}
