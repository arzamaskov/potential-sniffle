<?php

declare(strict_types=1);

namespace Src\Identity\Domain\User;

use InvalidArgumentException;

final readonly class Login
{
    public const int MIN_LENGTH = 3;

    public const int MAX_LENGTH = 32;

    private const string PATTERN = '/^[a-zA-Z0-9._-]+$/';

    private function __construct(private string $login) {}

    public static function from(string $login): self
    {
        $normalized = self::normalize($login);
        self::validate($normalized, $login);

        return new self($normalized);
    }

    private static function normalize(string $login): string
    {
        return strtolower(trim($login));
    }

    public function value(): string
    {
        return $this->login;
    }

    public function equals(self $other): bool
    {
        return $this->login === $other->login;
    }

    private static function validate(string $normalized, string $login): void
    {
        if ($normalized === '') {
            throw new InvalidArgumentException('Login cannot be empty');
        }

        if (strlen($normalized) < self::MIN_LENGTH) {
            throw new InvalidArgumentException('Login cannot be shorter than '.self::MIN_LENGTH.' characters');
        }

        if (strlen($normalized) > self::MAX_LENGTH) {
            throw new InvalidArgumentException('Login cannot be longer than '.self::MAX_LENGTH.' characters');
        }

        if (preg_match(self::PATTERN, $normalized) !== 1) {
            throw new InvalidArgumentException('Login '.$login.' contains invalid characters');
        }
    }
}
