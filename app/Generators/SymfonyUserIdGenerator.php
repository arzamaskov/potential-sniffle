<?php

declare(strict_types=1);

namespace App\Generators;

use Src\Identity\Application\User\UserIdGenerator;
use Src\Identity\Domain\User\UserId;
use Symfony\Component\Uid\Ulid;

final class SymfonyUserIdGenerator implements UserIdGenerator
{
    public function generate(): UserId
    {
        return UserId::from((new Ulid)->toBase32());
    }
}
