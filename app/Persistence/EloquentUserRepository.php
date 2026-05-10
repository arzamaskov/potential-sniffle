<?php

declare(strict_types=1);

namespace App\Persistence;

use App\Models\User as EloquentUser;
use Src\Identity\Domain\User\Login;
use Src\Identity\Domain\User\User as DomainUser;
use Src\Identity\Domain\User\UserRepository;

final readonly class EloquentUserRepository implements UserRepository
{
    public function __construct(private IdentityUserMapper $mapper) {}

    public function add(DomainUser $domainUser): void
    {
        $eloquentUser = $this->mapper->toEloquent($domainUser);
        $eloquentUser->save();
    }

    public function existsByLogin(Login $login): bool
    {
        return EloquentUser::query()
            ->where('login', $login->value())
            ->exists();
    }
}
