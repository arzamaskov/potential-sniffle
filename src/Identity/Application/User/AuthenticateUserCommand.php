<?php

declare(strict_types=1);

namespace Src\Identity\Application\User;

final class AuthenticateUserCommand
{
    public function __construct(public string $login, public string $password) {}
}
