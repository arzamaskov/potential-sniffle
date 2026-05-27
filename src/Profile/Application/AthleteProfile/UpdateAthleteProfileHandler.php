<?php

declare(strict_types=1);

namespace Src\Profile\Application\AthleteProfile;

use Src\Identity\Domain\User\UserId;
use Src\Profile\Domain\AthleteProfile\AthleteProfile;
use Src\Profile\Domain\AthleteProfile\AthleteProfileRepository;
use Src\Profile\Domain\AthleteProfile\BirthDate;
use Src\Profile\Domain\AthleteProfile\Gender;
use Src\Profile\Domain\AthleteProfile\HeartRate;
use Src\Profile\Domain\AthleteProfile\Height;
use Src\Profile\Domain\AthleteProfile\Weight;

final readonly class UpdateAthleteProfileHandler
{
    public function __construct(private AthleteProfileRepository $repository) {}

    public function handle(UpdateAthleteProfileCommand $command): void
    {
        $userId = UserId::from($command->userId);
        $profile = $this->repository->findByUserId($userId) ?? AthleteProfile::create($userId);

        $profile->updateAthleteParameters(
            is_null($command->birthDate) ? null : BirthDate::from($command->birthDate),
            is_null($command->gender) ? null : Gender::fromString($command->gender),
            is_null($command->heightCentimeters) ? null : Height::fromCentimeters($command->heightCentimeters),
            is_null($command->weightKilograms) ? null : Weight::fromKilograms($command->weightKilograms),
        );
        $profile->updateHeartRateSettings(
            is_null($command->maxHeartRate) ? null : HeartRate::from($command->maxHeartRate),
            is_null($command->restingHeartRate) ? null : HeartRate::from($command->restingHeartRate),
            is_null($command->thresholdHeartRate) ? null : HeartRate::from($command->thresholdHeartRate),
        );

        $this->repository->save($profile);
    }
}
