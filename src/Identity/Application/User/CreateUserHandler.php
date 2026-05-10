<?php

declare(strict_types=1);

namespace Src\Identity\Application\User;

use Src\Identity\Domain\User\Login;
use Src\Identity\Domain\User\User;
use Src\Identity\Domain\User\UserId;
use Src\Identity\Domain\User\UserRepository;

final readonly class CreateUserHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private UserIdGenerator $userIds,
        private PasswordHasher $passwordHasher,
    ) {}

    public function handle(CreateUserCommand $command): UserId
    {
        $userId = $this->userIds->generate();
        $passwordHash = $this->passwordHasher->hash($command->plainPassword);
        $user = new User($userId, Login::from($command->login), $passwordHash);
        $this->userRepository->add($user);

        return $userId;
    }
}
