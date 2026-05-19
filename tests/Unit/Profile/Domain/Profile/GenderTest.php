<?php

declare(strict_types=1);

namespace Tests\Unit\Profile\Domain\Profile;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Src\Profile\Domain\AthleteProfile\Gender;

final class GenderTest extends TestCase
{
    public function test_it_creates_male_gender(): void
    {
        $gender = Gender::fromString('male');

        $this->assertSame(Gender::Male, $gender);
        $this->assertSame('male', $gender->value);
    }

    public function test_it_creates_female_gender(): void
    {
        $gender = Gender::fromString('female');

        $this->assertSame(Gender::Female, $gender);
        $this->assertSame('female', $gender->value);
    }

    public function test_it_throws_exception_for_unknown_gender(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown gender');

        Gender::fromString('unknown');
    }
}
