<?php

declare(strict_types=1);

namespace Src\Identity\Application\User;

use Src\Identity\Domain\User\PasswordHash;

interface PasswordVerifier
{
    public function verify(string $plainPassword, PasswordHash $passwordHash): bool;
}
