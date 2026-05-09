<?php

declare(strict_types=1);

namespace Tests\Unit\Identity\Domain\User;

use PHPUnit\Framework\TestCase;
use Src\Identity\Domain\User\Login;
use Src\Identity\Domain\User\PasswordHash;
use Src\Identity\Domain\User\User;
use Src\Identity\Domain\User\UserId;
use Symfony\Component\Uid\Ulid;

class UserTest extends TestCase
{
    public function test_it_creates_user(): void
    {
        $id = UserId::from((new Ulid)->toBase32());
        $login = Login::from('user-login');
        $passwordHash = PasswordHash::from(password_hash('password', PASSWORD_ARGON2ID));

        $user = new User($id, $login, $passwordHash);

        $this->assertSame($id, $user->id());
        $this->assertSame($login, $user->login());
        $this->assertSame($passwordHash, $user->passwordHash());
    }

    public function test_it_changes_login(): void
    {
        $user = new User(
            UserId::from((new Ulid)->toBase32()),
            Login::from('old-login'),
            PasswordHash::from(password_hash('password', PASSWORD_ARGON2ID)),
        );
        $newLogin = Login::from('new-login');

        $user->changeLogin($newLogin);

        $this->assertSame($newLogin, $user->login());
    }

    public function test_it_changes_password_hash(): void
    {
        $user = new User(
            UserId::from((new Ulid)->toBase32()),
            Login::from('user-login'),
            PasswordHash::from(password_hash('password', PASSWORD_ARGON2ID)),
        );
        $newPasswordHash = PasswordHash::from(password_hash('new-password', PASSWORD_ARGON2ID));

        $user->changePasswordHash($newPasswordHash);

        $this->assertSame($newPasswordHash, $user->passwordHash());
    }
}
