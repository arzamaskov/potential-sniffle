<?php

declare(strict_types=1);

namespace App\Providers;

use App\Generators\SymfonyUserIdGenerator;
use App\Persistence\EloquentUserRepository;
use App\Security\LaravelPasswordHasher;
use App\Security\LaravelPasswordVerifier;
use Illuminate\Support\ServiceProvider;
use Src\Identity\Application\User\PasswordHasher;
use Src\Identity\Application\User\PasswordVerifier;
use Src\Identity\Application\User\UserIdGenerator;
use Src\Identity\Domain\User\UserRepository;

class IdentityServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UserRepository::class, EloquentUserRepository::class);
        $this->app->singleton(PasswordHasher::class, LaravelPasswordHasher::class);
        $this->app->singleton(UserIdGenerator::class, SymfonyUserIdGenerator::class);
        $this->app->singleton(PasswordVerifier::class, LaravelPasswordVerifier::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
