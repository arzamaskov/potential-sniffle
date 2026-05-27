<?php

declare(strict_types=1);

namespace Src\Profile\Domain\AthleteProfile;

use Src\Identity\Domain\User\UserId;

interface AthleteProfileRepository
{
    public function findByUserId(UserId $userId): ?AthleteProfile;

    public function save(AthleteProfile $athleteProfile): void;
}
