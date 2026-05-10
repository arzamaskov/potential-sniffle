<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Random\RandomException;
use Src\Identity\Application\User\CreateUserCommand;
use Src\Identity\Application\User\CreateUserHandler;
use Src\Identity\Application\User\LoginAlreadyTaken;

#[Signature('identity:create-user {login : User login, e.g. admin}')]
#[Description('Create a user account with a generated password')]
class CreateUserConsoleCommand extends Command
{
    /**
     * Execute the console command.
     *
     * @throws RandomException
     */
    public function handle(CreateUserHandler $handler): int
    {
        $login = (string) $this->argument('login');
        $plainPassword = $this->generatePassword();

        $command = new CreateUserCommand($login, $plainPassword);
        try {
            $handler->handle($command);
        } catch (LoginAlreadyTaken $e) {
            $this->error('Login already taken');

            return self::FAILURE;
        }

        $this->info('User created.');
        $this->info('Login: '.$login);
        $this->info('Generated password: '.$plainPassword);
        $this->warn('Store this password now. It will not be shown again.');

        return self::SUCCESS;
    }

    /**
     * @throws RandomException
     */
    private function generatePassword(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(24)), '+/', '-_'), '=');
    }
}
