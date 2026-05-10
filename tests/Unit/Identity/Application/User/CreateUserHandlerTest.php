<?php

declare(strict_types=1);

namespace Tests\Unit\Identity\Application\User;

use LogicException;
use PHPUnit\Framework\TestCase;
use Src\Identity\Application\User\CreateUserCommand;
use Src\Identity\Application\User\CreateUserHandler;
use Src\Identity\Application\User\PasswordHasher;
use Src\Identity\Application\User\UserIdGenerator;
use Src\Identity\Domain\User\PasswordHash;
use Src\Identity\Domain\User\User;
use Src\Identity\Domain\User\UserId;
use Src\Identity\Domain\User\UserRepository;

class CreateUserHandlerTest extends TestCase
{
    public function test_it_creates_user_with_normalized_login_and_hashed_password(): void
    {
        $userId = UserId::from('01HX8F5X4B9Z7N6Y2K3M4P5Q6R');
        $passwordHash = PasswordHash::from(password_hash('secret-password', PASSWORD_ARGON2ID));

        $users = new InMemoryUserRepository;
        $userIds = new StubUserIdGenerator($userId);
        $passwordHasher = new StubPasswordHasher($passwordHash);

        $handler = new CreateUserHandler($users, $userIds, $passwordHasher);

        $createdUserId = $handler->handle(
            new CreateUserCommand(' USER-login ', 'secret-password'),
        );

        $savedUser = $users->savedUser();

        $this->assertTrue($userId->equals($createdUserId));
        $this->assertTrue($userId->equals($savedUser->id()));
        $this->assertSame('user-login', $savedUser->login()->value());
        $this->assertSame($passwordHash, $savedUser->passwordHash());
        $this->assertSame('secret-password', $passwordHasher->plainPassword);
    }
}

final class InMemoryUserRepository implements UserRepository
{
    private ?User $addedUser = null;

    public function add(User $user): void
    {
        $this->addedUser = $user;
    }

    public function savedUser(): User
    {
        if (! $this->addedUser instanceof User) {
            throw new LogicException('User was not added to the repository.');
        }

        return $this->addedUser;
    }
}

final readonly class StubUserIdGenerator implements UserIdGenerator
{
    public function __construct(private UserId $userId) {}

    public function generate(): UserId
    {
        return $this->userId;
    }
}

final class StubPasswordHasher implements PasswordHasher
{
    public ?string $plainPassword = null;

    public function __construct(private readonly PasswordHash $passwordHash) {}

    public function hash(string $plainPassword): PasswordHash
    {
        $this->plainPassword = $plainPassword;

        return $this->passwordHash;
    }
}
