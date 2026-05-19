<?php

declare(strict_types=1);

namespace Tests\Unit\Profile\Domain\Profile;

use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Src\Profile\Domain\AthleteProfile\BirthDate;

class BirthDateTest extends TestCase
{
    public function test_it_creates_birth_date_from_string(): void
    {
        $birthDate = BirthDate::from('1990-05-15');

        $this->assertSame('1990-05-15', $birthDate->value()->format('Y-m-d'));
    }

    public function test_it_creates_birth_date_from_date(): void
    {
        $date = new DateTimeImmutable('1990-05-15');

        $birthDate = BirthDate::from($date);

        $this->assertSame('1990-05-15', $birthDate->value()->format('Y-m-d'));
    }

    public function test_it_calculates_age_at_given_date(): void
    {
        $birthDate = BirthDate::from('1990-05-15');

        $this->assertSame(35, $birthDate->ageAt(new DateTimeImmutable('2026-05-14')));
        $this->assertSame(36, $birthDate->ageAt(new DateTimeImmutable('2026-05-15')));
    }

    public function test_it_throws_exception_for_invalid_date_format(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Birth date must be in Y-m-d format');

        BirthDate::from('15.05.1990');
    }

    public function test_it_throws_exception_for_future_birth_date(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Birth date cannot be in the future');

        BirthDate::from('2999-05-15');
    }

    public function test_it_compares_birth_dates_by_value(): void
    {
        $birthDate = BirthDate::from('1990-05-15');
        $sameBirthDate = BirthDate::from('1990-05-15');
        $anotherBirthDate = BirthDate::from('1991-05-15');

        $this->assertTrue($birthDate->equals($sameBirthDate));
        $this->assertFalse($birthDate->equals($anotherBirthDate));
    }

    public function test_it_throws_exception_for_invalid_calendar_date(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Birth date must be in Y-m-d format');

        BirthDate::from('1990-02-31');
    }

    public function test_it_normalizes_birth_date_time(): void
    {
        $birthDate = BirthDate::from(new DateTimeImmutable('1990-05-15 15:30:45'));

        $this->assertSame('1990-05-15 00:00:00', $birthDate->value()->format('Y-m-d H:i:s'));
    }
}
