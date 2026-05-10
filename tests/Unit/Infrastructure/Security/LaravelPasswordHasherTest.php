<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Security;

use App\Security\LaravelPasswordHasher;
use Illuminate\Hashing\BcryptHasher;
use PHPUnit\Framework\TestCase;
use Src\Identity\Application\User\PasswordHasher;

class LaravelPasswordHasherTest extends TestCase
{
    public function test_it_hashes_plain_password(): void
    {
        $passwordHasher = $this->passwordHasher();

        $passwordHash = $passwordHasher->hash('secret-password');

        $this->assertTrue(password_verify('secret-password', $passwordHash->value()));
        $this->assertFalse(password_verify('wrong-password', $passwordHash->value()));
    }

    private function passwordHasher(): PasswordHasher
    {
        return new LaravelPasswordHasher(new BcryptHasher);
    }
}
