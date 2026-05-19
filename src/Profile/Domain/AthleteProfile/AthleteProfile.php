<?php

declare(strict_types=1);

namespace Src\Profile\Domain\AthleteProfile;

use DateTimeImmutable;
use InvalidArgumentException;
use Src\Identity\Domain\User\UserId;

final class AthleteProfile
{
    private ?BirthDate $birthDate = null;

    private ?Gender $gender = null;

    private ?Height $height = null;

    private ?Weight $weight = null;

    private ?HeartRate $maxHeartRate = null;

    private ?HeartRate $restingHeartRate = null;

    private ?HeartRate $thresholdHeartRate = null;

    private function __construct(
        private readonly UserId $userId,
    ) {}

    public static function create(UserId $userId): self
    {
        return new self($userId);
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function birthDate(): ?BirthDate
    {
        return $this->birthDate;
    }

    public function gender(): ?Gender
    {
        return $this->gender;
    }

    public function height(): ?Height
    {
        return $this->height;
    }

    public function weight(): ?Weight
    {
        return $this->weight;
    }

    public function maxHeartRate(): ?HeartRate
    {
        return $this->maxHeartRate;
    }

    public function restingHeartRate(): ?HeartRate
    {
        return $this->restingHeartRate;
    }

    public function thresholdHeartRate(): ?HeartRate
    {
        return $this->thresholdHeartRate;
    }

    public function updateAthleteParameters(
        ?BirthDate $birthDate,
        ?Gender $gender,
        ?Height $height,
        ?Weight $weight
    ): void {
        $this->birthDate = $birthDate;
        $this->gender = $gender;
        $this->height = $height;
        $this->weight = $weight;
    }

    public function updateHeartRateSettings(
        ?HeartRate $maxHeartRate,
        ?HeartRate $restingHeartRate,
        ?HeartRate $thresholdHeartRate
    ): void {
        if (
            $thresholdHeartRate !== null
            && $maxHeartRate !== null
            && $thresholdHeartRate->greaterThan($maxHeartRate)
        ) {
            throw new InvalidArgumentException('Heart rate values are inconsistent');
        }
        $this->maxHeartRate = $maxHeartRate;
        $this->restingHeartRate = $restingHeartRate;
        $this->thresholdHeartRate = $thresholdHeartRate;
    }

    public function ageAt(DateTimeImmutable $date): ?int
    {
        return $this->birthDate?->ageAt($date);
    }
}
