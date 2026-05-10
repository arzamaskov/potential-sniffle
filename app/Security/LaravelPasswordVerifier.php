<?php

declare(strict_types=1);

namespace App\Security;

use Illuminate\Contracts\Hashing\Hasher;
use Src\Identity\Application\User\PasswordVerifier;
use Src\Identity\Domain\User\PasswordHash;

final readonly class LaravelPasswordVerifier implements PasswordVerifier
{
    public function __construct(private Hasher $hasher) {}

    public function verify(string $plainPassword, PasswordHash $passwordHash): bool
    {
        return $this->hasher->check($plainPassword, $passwordHash->value());
    }
}
