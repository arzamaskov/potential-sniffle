<?php

declare(strict_types=1);

namespace Tests\Unit\Profile\Domain\Profile;

use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Src\Identity\Domain\User\UserId;
use Src\Profile\Domain\AthleteProfile\AthleteProfile;
use Src\Profile\Domain\AthleteProfile\BirthDate;
use Src\Profile\Domain\AthleteProfile\Gender;
use Src\Profile\Domain\AthleteProfile\HeartRate;
use Src\Profile\Domain\AthleteProfile\Height;
use Src\Profile\Domain\AthleteProfile\Weight;
use Symfony\Component\Uid\Ulid;

class ProfileTest extends TestCase
{
    public function test_it_creates_empty_profile_for_user(): void
    {
        $userId = $this->userId();

        $profile = AthleteProfile::create($userId);

        $this->assertSame($userId, $profile->userId());
        $this->assertNull($profile->birthDate());
        $this->assertNull($profile->gender());
        $this->assertNull($profile->height());
        $this->assertNull($profile->weight());
        $this->assertNull($profile->maxHeartRate());
        $this->assertNull($profile->restingHeartRate());
        $this->assertNull($profile->thresholdHeartRate());
    }

    public function test_it_updates_athlete_parameters(): void
    {
        $profile = AthleteProfile::create($this->userId());
        $birthDate = BirthDate::from('1990-05-15');
        $gender = Gender::fromString('male');
        $height = Height::fromCentimeters(180);
        $weight = Weight::fromKilograms(75.5);

        $profile->updateAthleteParameters(
            birthDate: $birthDate,
            gender: $gender,
            height: $height,
            weight: $weight,
        );

        $this->assertSame($birthDate, $profile->birthDate());
        $this->assertSame($gender, $profile->gender());
        $this->assertSame($height, $profile->height());
        $this->assertSame($weight, $profile->weight());
    }

    public function test_it_updates_heart_rate_settings(): void
    {
        $profile = AthleteProfile::create($this->userId());
        $maxHeartRate = HeartRate::from(190);
        $restingHeartRate = HeartRate::from(48);
        $thresholdHeartRate = HeartRate::from(168);

        $profile->updateHeartRateSettings(
            maxHeartRate: $maxHeartRate,
            restingHeartRate: $restingHeartRate,
            thresholdHeartRate: $thresholdHeartRate,
        );

        $this->assertSame($maxHeartRate, $profile->maxHeartRate());
        $this->assertSame($restingHeartRate, $profile->restingHeartRate());
        $this->assertSame($thresholdHeartRate, $profile->thresholdHeartRate());
    }

    public function test_it_accepts_optional_parameters(): void
    {
        $profile = AthleteProfile::create($this->userId());

        $profile->updateAthleteParameters(
            birthDate: null,
            gender: null,
            height: null,
            weight: null,
        );
        $profile->updateHeartRateSettings(
            maxHeartRate: null,
            restingHeartRate: null,
            thresholdHeartRate: null,
        );

        $this->assertNull($profile->birthDate());
        $this->assertNull($profile->gender());
        $this->assertNull($profile->height());
        $this->assertNull($profile->weight());
        $this->assertNull($profile->maxHeartRate());
        $this->assertNull($profile->restingHeartRate());
        $this->assertNull($profile->thresholdHeartRate());
    }

    public function test_it_calculates_age_from_birth_date(): void
    {
        $profile = AthleteProfile::create($this->userId());
        $profile->updateAthleteParameters(
            birthDate: BirthDate::from('1990-05-15'),
            gender: null,
            height: null,
            weight: null,
        );

        $age = $profile->ageAt(new DateTimeImmutable('2026-05-15'));

        $this->assertSame(36, $age);
    }

    public function test_it_returns_null_age_without_birth_date(): void
    {
        $profile = AthleteProfile::create($this->userId());

        $this->assertNull($profile->ageAt(new DateTimeImmutable('2026-05-15')));
    }

    private function userId(): UserId
    {
        return UserId::from((new Ulid)->toBase32());
    }

    public function test_it_rejects_invalid_heart_rate_order(): void
    {
        $profile = AthleteProfile::create($this->userId());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Heart rate values are inconsistent');

        $profile->updateHeartRateSettings(
            maxHeartRate: HeartRate::from(160),
            restingHeartRate: HeartRate::from(50),
            thresholdHeartRate: HeartRate::from(168),
        );
    }

    public function test_it_replaces_existing_athlete_parameters(): void
    {
        $profile = AthleteProfile::create($this->userId());

        $profile->updateAthleteParameters(
            birthDate: BirthDate::from('1990-05-15'),
            gender: Gender::fromString('male'),
            height: Height::fromCentimeters(180),
            weight: Weight::fromKilograms(75.5),
        );

        $profile->updateAthleteParameters(
            birthDate: BirthDate::from('1991-06-20'),
            gender: Gender::fromString('female'),
            height: Height::fromCentimeters(170),
            weight: Weight::fromKilograms(65),
        );

        $this->assertSame('1991-06-20', $profile->birthDate()?->toString());
        $this->assertSame(Gender::Female, $profile->gender());
        $this->assertSame(170, $profile->height()?->centimeters());
        $this->assertSame(65.0, $profile->weight()?->kilograms());
    }
}
