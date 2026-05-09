<?php

declare(strict_types=1);

namespace Src\Identity\Domain\User;

final class User
{
    public function __construct(
        private readonly UserId $id,
        private Login $login,
        private PasswordHash $passwordHash,
    ) {}

    public function id(): UserId
    {
        return $this->id;
    }

    public function login(): Login
    {
        return $this->login;
    }

    public function passwordHash(): PasswordHash
    {
        return $this->passwordHash;
    }

    public function changeLogin(Login $newLogin): void
    {
        $this->login = $newLogin;
    }

    public function changePasswordHash(PasswordHash $newPasswordHash): void
    {
        $this->passwordHash = $newPasswordHash;
    }
}
