<?php

declare(strict_types=1);

namespace Src\Identity\Application\User;

use Src\Identity\Domain\User\PasswordHash;

interface PasswordHasher
{
    public function hash(string $plainPassword): PasswordHash;
}
