<?php

declare(strict_types=1);

namespace Src\Identity\Application\User;

final class CreateUserCommand
{
    public function __construct(public string $login, public string $plainPassword) {}
}
