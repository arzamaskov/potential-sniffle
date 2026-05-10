<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Security;

use App\Security\LaravelPasswordVerifier;
use Illuminate\Hashing\BcryptHasher;
use PHPUnit\Framework\TestCase;
use Src\Identity\Application\User\PasswordVerifier;
use Src\Identity\Domain\User\PasswordHash;

class LaravelPasswordVerifierTest extends TestCase
{
    public function test_it_verifies_plain_password_against_password_hash(): void
    {
        $passwordVerifier = $this->passwordVerifier();
        $passwordHash = PasswordHash::from(password_hash('secret-password', PASSWORD_BCRYPT));

        $this->assertTrue($passwordVerifier->verify('secret-password', $passwordHash));
        $this->assertFalse($passwordVerifier->verify('wrong-password', $passwordHash));
    }

    private function passwordVerifier(): PasswordVerifier
    {
        return new LaravelPasswordVerifier(new BcryptHasher);
    }
}
