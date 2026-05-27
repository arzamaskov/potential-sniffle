<?php

declare(strict_types=1);

namespace Src\Profile\Application\AthleteProfile;

final class UpdateAthleteProfileCommand
{
    public function __construct(
        public string $userId,
        public ?string $birthDate,
        public ?string $gender,
        public ?int $heightCentimeters,
        public ?float $weightKilograms,
        public ?int $maxHeartRate,
        public ?int $restingHeartRate,
        public ?int $thresholdHeartRate,
    ) {}
}
