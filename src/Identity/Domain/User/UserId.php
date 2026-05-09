<?php

declare(strict_types=1);

namespace Src\Identity\Domain\User;

use InvalidArgumentException;

final readonly class UserId
{
    private const string PATTERN = '/^[0-7][0-9A-HJKMNP-TV-Z]{25}$/';

    private function __construct(private string $ulid)
    {
        if (preg_match(self::PATTERN, $this->ulid) !== 1) {
            throw new InvalidArgumentException('Invalid ULID format');
        }
    }

    public static function from(string $ulid): self
    {
        return new self($ulid);
    }

    public function value(): string
    {
        return $this->ulid;
    }

    public function equals(self $other): bool
    {
        return $this->ulid === $other->ulid;
    }
}
