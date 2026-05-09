<?php

declare(strict_types=1);

namespace Src\Identity\Domain\User;

use InvalidArgumentException;

final readonly class PasswordHash
{
    private function __construct(private string $passwordHash)
    {
        if (! password_get_info($passwordHash)['algo']) {
            throw new InvalidArgumentException('Invalid password hash');
        }
    }

    public static function from(string $passwordHash): self
    {
        return new self($passwordHash);
    }

    public function value(): string
    {
        return $this->passwordHash;
    }

    public function equals(self $other): bool
    {
        return $this->passwordHash === $other->passwordHash;
    }
}
