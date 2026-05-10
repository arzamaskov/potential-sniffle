<?php

declare(strict_types=1);

namespace Src\Identity\Domain\User;

interface UserRepository
{
    public function add(User $user): void;
}
