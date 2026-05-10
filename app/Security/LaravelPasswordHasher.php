<?php

declare(strict_types=1);

namespace App\Security;

use Illuminate\Contracts\Hashing\Hasher;
use Src\Identity\Application\User\PasswordHasher;
use Src\Identity\Domain\User\PasswordHash;

final readonly class LaravelPasswordHasher implements PasswordHasher
{
    public function __construct(private Hasher $hasher) {}

    public function hash(string $plainPassword): PasswordHash
    {
        return PasswordHash::from($this->hasher->make($plainPassword));
    }
}
