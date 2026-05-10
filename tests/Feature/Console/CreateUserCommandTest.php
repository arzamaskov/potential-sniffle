<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Console\Command\Command;
use Tests\TestCase;

class CreateUserCommandTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_user_and_prints_generated_password(): void
    {
        $this->withoutMockingConsoleOutput();

        $exitCode = $this->artisan('identity:create-user admin');
        $output = $this->app->make(Kernel::class)->output();

        $user = $this->assertUserWasCreated('admin');
        $generatedPassword = $this->extractGeneratedPassword($output);

        $this->assertSame(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString('User created.', $output);
        $this->assertStringContainsString('Login: admin', $output);
        $this->assertStringContainsString('Generated password: ', $output);
        $this->assertStringContainsString('Store this password now. It will not be shown again.', $output);
        $this->assertTrue(password_verify($generatedPassword, $user['password_hash']));
    }

    #[Test]
    public function it_fails_when_login_is_already_taken(): void
    {
        $this->withoutMockingConsoleOutput();

        $this->artisan('identity:create-user admin');

        $exitCode = $this->artisan('identity:create-user admin');
        $output = $this->app->make(Kernel::class)->output();

        $this->assertSame(Command::FAILURE, $exitCode);
        $this->assertStringContainsString('Login already taken', $output);
        $this->assertSame(
            1,
            (int) $this->app['db']
                ->table('users')
                ->where('login', 'admin')
                ->count(),
        );
    }

    /**
     * @return array{id: string, login: string, password_hash: string}
     */
    private function assertUserWasCreated(string $login): array
    {
        $user = $this->app['db']
            ->table('users')
            ->where('login', $login)
            ->first();

        $this->assertNotNull($user);

        return [
            'id' => (string) $user->id,
            'login' => (string) $user->login,
            'password_hash' => (string) $user->password_hash,
        ];
    }

    private function extractGeneratedPassword(string $output): string
    {
        preg_match('/Generated password: (?P<password>\\S+)/', $output, $matches);

        $this->assertArrayHasKey('password', $matches);

        return $matches['password'];
    }
}
