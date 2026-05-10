<?php

declare(strict_types=1);

namespace Src\Identity\Application\User;

use Src\Identity\Domain\User\Login;
use Src\Identity\Domain\User\UserId;
use Src\Identity\Domain\User\UserRepository;

final readonly class AuthenticateUserHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private PasswordVerifier $passwordVerifier
    ) {}

    public function handle(AuthenticateUserCommand $command): UserId
    {
        $login = Login::from($command->login);
        $user = $this->userRepository->findByLogin($login);
        if ($user === null) {
            throw new InvalidCredentials("User '{$command->login}' not found");
        }

        if (! $this->passwordVerifier->verify($command->password, $user->passwordHash())) {
            throw new InvalidCredentials;
        }

        return $user->id();
    }
}
