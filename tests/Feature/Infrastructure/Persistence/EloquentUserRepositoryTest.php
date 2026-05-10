<?php

declare(strict_types=1);

namespace Tests\Feature\Infrastructure\Persistence;

use App\Persistence\EloquentUserRepository;
use App\Persistence\IdentityUserMapper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Identity\Domain\User\Login;
use Src\Identity\Domain\User\PasswordHash;
use Src\Identity\Domain\User\User;
use Src\Identity\Domain\User\UserId;
use Src\Identity\Domain\User\UserRepository;
use Tests\TestCase;

class EloquentUserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_adds_user(): void
    {
        $repository = $this->repository();
        $user = $this->user();

        $repository->add($user);

        $this->assertDatabaseHas('users', [
            'id' => $user->id()->value(),
            'login' => 'user-login',
            'password_hash' => $user->passwordHash()->value(),
        ]);
    }

    public function test_it_checks_user_existence_by_normalized_login(): void
    {
        $repository = $this->repository();

        $repository->add($this->user());

        $this->assertTrue($repository->existsByLogin(Login::from(' USER-login ')));
        $this->assertFalse($repository->existsByLogin(Login::from('another-login')));
    }

    private function repository(): UserRepository
    {
        return new EloquentUserRepository(new IdentityUserMapper);
    }

    private function user(): User
    {
        return new User(
            UserId::from('01HX8F5X4B9Z7N6Y2K3M4P5Q6R'),
            Login::from(' USER-login '),
            PasswordHash::from(password_hash('secret-password', PASSWORD_ARGON2ID)),
        );
    }
}
