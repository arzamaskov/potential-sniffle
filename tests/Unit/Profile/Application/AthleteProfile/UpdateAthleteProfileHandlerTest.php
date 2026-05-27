<?php

declare(strict_types=1);

namespace Tests\Unit\Profile\Application\AthleteProfile;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Src\Identity\Domain\User\UserId;
use Src\Profile\Application\AthleteProfile\UpdateAthleteProfileCommand;
use Src\Profile\Application\AthleteProfile\UpdateAthleteProfileHandler;
use Src\Profile\Domain\AthleteProfile\AthleteProfile;
use Src\Profile\Domain\AthleteProfile\BirthDate;
use Src\Profile\Domain\AthleteProfile\Gender;
use Src\Profile\Domain\AthleteProfile\HeartRate;
use Src\Profile\Domain\AthleteProfile\Height;
use Src\Profile\Domain\AthleteProfile\Weight;

class UpdateAthleteProfileHandlerTest extends TestCase
{
    public function test_it_creates_profile_when_user_has_no_profile(): void
    {
        $profiles = new InMemoryAthleteProfileRepository;
        $handler = new UpdateAthleteProfileHandler($profiles);

        $handler->handle(new UpdateAthleteProfileCommand(
            userId: '01HX8F5X4B9Z7N6Y2K3M4P5Q6R',
            birthDate: '1990-05-15',
            gender: 'male',
            heightCentimeters: 180,
            weightKilograms: 75.5,
            maxHeartRate: 190,
            restingHeartRate: 48,
            thresholdHeartRate: 168,
        ));

        $savedProfile = $profiles->savedProfile();

        $this->assertTrue(UserId::from('01HX8F5X4B9Z7N6Y2K3M4P5Q6R')->equals($profiles->lastSearchedUserId()));
        $this->assertTrue(UserId::from('01HX8F5X4B9Z7N6Y2K3M4P5Q6R')->equals($savedProfile->userId()));
        $this->assertSame('1990-05-15', $savedProfile->birthDate()?->toString());
        $this->assertSame(Gender::Male, $savedProfile->gender());
        $this->assertSame(180, $savedProfile->height()?->centimeters());
        $this->assertSame(75.5, $savedProfile->weight()?->kilograms());
        $this->assertSame(190, $savedProfile->maxHeartRate()?->value());
        $this->assertSame(48, $savedProfile->restingHeartRate()?->value());
        $this->assertSame(168, $savedProfile->thresholdHeartRate()?->value());
    }

    public function test_it_updates_existing_profile(): void
    {
        $userId = UserId::from('01HX8F5X4B9Z7N6Y2K3M4P5Q6R');
        $existingProfile = AthleteProfile::create($userId);
        $existingProfile->updateAthleteParameters(
            BirthDate::from('1989-01-10'),
            Gender::Male,
            Height::fromCentimeters(178),
            Weight::fromKilograms(74.0),
        );
        $existingProfile->updateHeartRateSettings(
            HeartRate::from(188),
            HeartRate::from(50),
            HeartRate::from(166),
        );

        $profiles = new InMemoryAthleteProfileRepository($existingProfile);
        $handler = new UpdateAthleteProfileHandler($profiles);

        $handler->handle(new UpdateAthleteProfileCommand(
            userId: '01HX8F5X4B9Z7N6Y2K3M4P5Q6R',
            birthDate: '1990-05-15',
            gender: 'female',
            heightCentimeters: 170,
            weightKilograms: 65.5,
            maxHeartRate: 192,
            restingHeartRate: 45,
            thresholdHeartRate: 171,
        ));

        $savedProfile = $profiles->savedProfile();

        $this->assertSame($existingProfile, $savedProfile);
        $this->assertSame('1990-05-15', $savedProfile->birthDate()?->toString());
        $this->assertSame(Gender::Female, $savedProfile->gender());
        $this->assertSame(170, $savedProfile->height()?->centimeters());
        $this->assertSame(65.5, $savedProfile->weight()?->kilograms());
        $this->assertSame(192, $savedProfile->maxHeartRate()?->value());
        $this->assertSame(45, $savedProfile->restingHeartRate()?->value());
        $this->assertSame(171, $savedProfile->thresholdHeartRate()?->value());
    }

    public function test_it_clears_optional_profile_values(): void
    {
        $userId = UserId::from('01HX8F5X4B9Z7N6Y2K3M4P5Q6R');
        $existingProfile = AthleteProfile::create($userId);
        $existingProfile->updateAthleteParameters(
            BirthDate::from('1990-05-15'),
            Gender::Male,
            Height::fromCentimeters(180),
            Weight::fromKilograms(75.5),
        );
        $existingProfile->updateHeartRateSettings(
            HeartRate::from(190),
            HeartRate::from(48),
            HeartRate::from(168),
        );

        $profiles = new InMemoryAthleteProfileRepository($existingProfile);
        $handler = new UpdateAthleteProfileHandler($profiles);

        $handler->handle(new UpdateAthleteProfileCommand(
            userId: '01HX8F5X4B9Z7N6Y2K3M4P5Q6R',
            birthDate: null,
            gender: null,
            heightCentimeters: null,
            weightKilograms: null,
            maxHeartRate: null,
            restingHeartRate: null,
            thresholdHeartRate: null,
        ));

        $savedProfile = $profiles->savedProfile();

        $this->assertNull($savedProfile->birthDate());
        $this->assertNull($savedProfile->gender());
        $this->assertNull($savedProfile->height());
        $this->assertNull($savedProfile->weight());
        $this->assertNull($savedProfile->maxHeartRate());
        $this->assertNull($savedProfile->restingHeartRate());
        $this->assertNull($savedProfile->thresholdHeartRate());
    }

    public function test_it_does_not_save_profile_when_domain_validation_fails(): void
    {
        $profiles = new InMemoryAthleteProfileRepository;
        $handler = new UpdateAthleteProfileHandler($profiles);

        try {
            $handler->handle(new UpdateAthleteProfileCommand(
                userId: '01HX8F5X4B9Z7N6Y2K3M4P5Q6R',
                birthDate: '1990-05-15',
                gender: 'male',
                heightCentimeters: 180,
                weightKilograms: 75.5,
                maxHeartRate: 160,
                restingHeartRate: 48,
                thresholdHeartRate: 168,
            ));

            $this->fail('Expected invalid heart rate values.');
        } catch (InvalidArgumentException $exception) {
            $this->assertSame('Heart rate values are inconsistent', $exception->getMessage());
            $this->assertFalse($profiles->wasSaved());
        }
    }
}
