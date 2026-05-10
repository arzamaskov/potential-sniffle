<?php

declare(strict_types=1);

namespace Tests\Unit\Identity\Application\User;

use PHPUnit\Framework\TestCase;
use Src\Identity\Application\User\AuthenticateUserCommand;
use Src\Identity\Application\User\AuthenticateUserHandler;
use Src\Identity\Application\User\InvalidCredentials;
use Src\Identity\Application\User\PasswordVerifier;
use Src\Identity\Domain\User\Login;
use Src\Identity\Domain\User\PasswordHash;
use Src\Identity\Domain\User\User;
use Src\Identity\Domain\User\UserId;

class AuthenticateUserHandlerTest extends TestCase
{
    public function test_it_authenticates_user_by_normalized_login_and_plain_password(): void
    {
        $user = new User(
            UserId::from('01HX8F5X4B9Z7N6Y2K3M4P5Q6R'),
            Login::from('user-login'),
            PasswordHash::from(password_hash('secret-password', PASSWORD_ARGON2ID)),
        );

        $users = new InMemoryUserRepository($user);
        $passwordVerifier = new StubPasswordVerifier(true);
        $handler = new AuthenticateUserHandler($users, $passwordVerifier);

        $authenticatedUserId = $handler->handle(
            new AuthenticateUserCommand(' USER-login ', 'secret-password'),
        );

        $this->assertTrue($user->id()->equals($authenticatedUserId));
        $this->assertTrue(Login::from('user-login')->equals($users->lastSearchedLogin()));
        $this->assertSame('secret-password', $passwordVerifier->plainPassword);
        $this->assertSame($user->passwordHash(), $passwordVerifier->passwordHash);
    }

    public function test_it_rejects_unknown_login(): void
    {
        $users = new InMemoryUserRepository;
        $passwordVerifier = new StubPasswordVerifier(true);
        $handler = new AuthenticateUserHandler($users, $passwordVerifier);

        try {
            $handler->handle(new AuthenticateUserCommand('unknown-login', 'secret-password'));

            $this->fail('Expected invalid credentials.');
        } catch (InvalidCredentials) {
            $this->assertTrue(Login::from('unknown-login')->equals($users->lastSearchedLogin()));
            $this->assertNull($passwordVerifier->plainPassword);
            $this->assertNull($passwordVerifier->passwordHash);
        }
    }

    public function test_it_rejects_invalid_password(): void
    {
        $user = new User(
            UserId::from('01HX8F5X4B9Z7N6Y2K3M4P5Q6R'),
            Login::from('user-login'),
            PasswordHash::from(password_hash('secret-password', PASSWORD_ARGON2ID)),
        );

        $users = new InMemoryUserRepository($user);
        $passwordVerifier = new StubPasswordVerifier(false);
        $handler = new AuthenticateUserHandler($users, $passwordVerifier);

        try {
            $handler->handle(new AuthenticateUserCommand('user-login', 'wrong-password'));

            $this->fail('Expected invalid credentials.');
        } catch (InvalidCredentials) {
            $this->assertSame('wrong-password', $passwordVerifier->plainPassword);
            $this->assertSame($user->passwordHash(), $passwordVerifier->passwordHash);
        }
    }
}

final class StubPasswordVerifier implements PasswordVerifier
{
    public ?string $plainPassword = null;

    public ?PasswordHash $passwordHash = null;

    public function __construct(private readonly bool $isValid) {}

    public function verify(string $plainPassword, PasswordHash $passwordHash): bool
    {
        $this->plainPassword = $plainPassword;
        $this->passwordHash = $passwordHash;

        return $this->isValid;
    }
}
