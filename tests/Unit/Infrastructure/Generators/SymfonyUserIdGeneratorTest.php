<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Generators;

use App\Generators\SymfonyUserIdGenerator;
use PHPUnit\Framework\TestCase;
use Src\Identity\Application\User\UserIdGenerator;

class SymfonyUserIdGeneratorTest extends TestCase
{
    public function test_it_generates_user_id(): void
    {
        $generator = $this->generator();

        $userId = $generator->generate();

        $this->assertMatchesRegularExpression('/^[0-7][0-9A-HJKMNP-TV-Z]{25}$/', $userId->value());
    }

    private function generator(): UserIdGenerator
    {
        return new SymfonyUserIdGenerator;
    }
}
