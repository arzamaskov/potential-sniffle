<?php

declare(strict_types=1);

namespace Tests\Unit\Profile\Application\AthleteProfile;

use LogicException;
use Src\Identity\Domain\User\UserId;
use Src\Profile\Domain\AthleteProfile\AthleteProfile;
use Src\Profile\Domain\AthleteProfile\AthleteProfileRepository;

final class InMemoryAthleteProfileRepository implements AthleteProfileRepository
{
    /**
     * @var array<string, AthleteProfile>
     */
    private array $profiles = [];

    private ?UserId $lastSearchedUserId = null;

    private ?AthleteProfile $savedProfile = null;

    public function __construct(AthleteProfile ...$profiles)
    {
        foreach ($profiles as $profile) {
            $this->profiles[$profile->userId()->value()] = $profile;
        }
    }

    public function findByUserId(UserId $userId): ?AthleteProfile
    {
        $this->lastSearchedUserId = $userId;

        return $this->profiles[$userId->value()] ?? null;
    }

    public function save(AthleteProfile $profile): void
    {
        $this->profiles[$profile->userId()->value()] = $profile;
        $this->savedProfile = $profile;
    }

    public function wasSaved(): bool
    {
        return $this->savedProfile instanceof AthleteProfile;
    }

    public function savedProfile(): AthleteProfile
    {
        if (! $this->savedProfile instanceof AthleteProfile) {
            throw new LogicException('Athlete profile was not saved.');
        }

        return $this->savedProfile;
    }

    public function lastSearchedUserId(): UserId
    {
        if (! $this->lastSearchedUserId instanceof UserId) {
            throw new LogicException('Athlete profile was not searched.');
        }

        return $this->lastSearchedUserId;
    }
}
