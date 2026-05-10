<?php

declare(strict_types=1);

namespace App\Persistence;

use App\Models\User as EloquentUser;
use Src\Identity\Domain\User\Login;
use Src\Identity\Domain\User\PasswordHash;
use Src\Identity\Domain\User\User as DomainUser;
use Src\Identity\Domain\User\UserId;

final class IdentityUserMapper
{
    public function toDomain(EloquentUser $eloquentUser): DomainUser
    {
        return new DomainUser(
            id: UserId::from($eloquentUser->getAttribute('id')),
            login: Login::from($eloquentUser->getAttribute('login')),
            passwordHash: PasswordHash::from($eloquentUser->getAttribute('password_hash')),
        );
    }

    public function toEloquent(DomainUser $domainUser): EloquentUser
    {
        $eloquentUser = new EloquentUser;
        $eloquentUser->setAttribute('id', $domainUser->id()->value());
        $eloquentUser->setAttribute('login', $domainUser->login()->value());
        $eloquentUser->setAttribute('password_hash', $domainUser->passwordHash()->value());

        return $eloquentUser;
    }
}
