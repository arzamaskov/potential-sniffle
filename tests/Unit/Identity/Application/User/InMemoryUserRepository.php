<?php

declare(strict_types=1);

namespace Tests\Unit\Identity\Application\User;

use LogicException;
use Src\Identity\Domain\User\Login;
use Src\Identity\Domain\User\User;
use Src\Identity\Domain\User\UserRepository;

final class InMemoryUserRepository implements UserRepository
{
    /**
     * @var list<User>
     */
    private array $users;

    private ?User $addedUser = null;

    private ?Login $lastCheckedLogin = null;

    private ?Login $lastSearchedLogin = null;

    public function __construct(User ...$users)
    {
        $this->users = $users;
    }

    public function add(User $domainUser): void
    {
        $this->users[] = $domainUser;
        $this->addedUser = $domainUser;
    }

    public function existsByLogin(Login $login): bool
    {
        $this->lastCheckedLogin = $login;

        return $this->searchByLogin($login) instanceof User;
    }

    public function findByLogin(Login $login): ?User
    {
        $this->lastSearchedLogin = $login;

        return $this->searchByLogin($login);
    }

    public function wasUserAdded(): bool
    {
        return $this->addedUser instanceof User;
    }

    public function lastCheckedLogin(): Login
    {
        if (! $this->lastCheckedLogin instanceof Login) {
            throw new LogicException('Login was not checked.');
        }

        return $this->lastCheckedLogin;
    }

    public function lastSearchedLogin(): Login
    {
        if (! $this->lastSearchedLogin instanceof Login) {
            throw new LogicException('Login was not searched.');
        }

        return $this->lastSearchedLogin;
    }

    public function savedUser(): User
    {
        if (! $this->addedUser instanceof User) {
            throw new LogicException('User was not added to the repository.');
        }

        return $this->addedUser;
    }

    private function searchByLogin(Login $login): ?User
    {
        foreach ($this->users as $user) {
            if ($user->login()->equals($login)) {
                return $user;
            }
        }

        return null;
    }
}
