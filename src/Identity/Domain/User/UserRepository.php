<?php

declare(strict_types=1);

namespace Src\Identity\Domain\User;

interface UserRepository
{
    public function add(User $domainUser): void;

    public function existsByLogin(Login $login): bool;

    public function findByLogin(Login $login): ?User;
}
