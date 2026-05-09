<?php

declare(strict_types=1);

namespace Tests\Unit\Identity\Domain\User;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Src\Identity\Domain\User\PasswordHash;

class PasswordHashTest extends TestCase
{
    public function test_it_creates_valid_password_hash(): void
    {
        $hash = password_hash('password', PASSWORD_ARGON2ID);
        $passwordHash = PasswordHash::from($hash);
        $this->assertSame($hash, $passwordHash->value());
    }

    public function test_it_compares_password_hashes_by_value(): void
    {
        $hash = password_hash('password', PASSWORD_ARGON2ID);
        $passwordHash = PasswordHash::from($hash);
        $samePasswordHash = PasswordHash::from($hash);
        $anotherPasswordHash = PasswordHash::from(password_hash('another-password', PASSWORD_ARGON2ID));

        $this->assertTrue($passwordHash->equals($samePasswordHash));
        $this->assertFalse($passwordHash->equals($anotherPasswordHash));
    }

    public function test_it_throws_exception_for_invalid_password_hash(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid password hash');

        PasswordHash::from('not-valid-hash');
    }
}
