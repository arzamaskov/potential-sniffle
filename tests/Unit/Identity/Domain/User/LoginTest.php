<?php

declare(strict_types=1);

namespace Tests\Unit\Identity\Domain\User;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Src\Identity\Domain\User\Login;

class LoginTest extends TestCase
{
    public function test_it_creates_valid_login(): void
    {
        $login = Login::from('user-login');
        $this->assertSame('user-login', $login->value());
    }

    public function test_it_accepts_login_with_minimum_length(): void
    {
        $login = Login::from(str_repeat('a', Login::MIN_LENGTH));

        $this->assertSame(str_repeat('a', Login::MIN_LENGTH), $login->value());
    }

    public function test_it_accepts_login_with_maximum_length(): void
    {
        $login = Login::from(str_repeat('a', Login::MAX_LENGTH));

        $this->assertSame(str_repeat('a', Login::MAX_LENGTH), $login->value());
    }

    public function test_it_accepts_allowed_special_characters(): void
    {
        $login = Login::from('user.name_1-test');

        $this->assertSame('user.name_1-test', $login->value());
    }

    public function test_it_throws_exception_for_empty_login(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Login cannot be empty');

        Login::from('   ');
    }

    public function test_it_throws_exception_for_too_short_login(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Login cannot be shorter than '.Login::MIN_LENGTH.' characters');

        Login::from('a');
    }

    public function test_it_throws_exception_for_too_long_login(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Login cannot be longer than '.Login::MAX_LENGTH.' characters');

        Login::from(str_repeat('a', Login::MAX_LENGTH + 1));
    }

    public function test_it_throws_exception_for_login_contains_invalid_characters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Login user login contains invalid characters');

        Login::from('user login');
    }

    public function test_it_normalizes_login_to_lowercase(): void
    {
        $login = Login::from('USER-login');
        $this->assertSame('user-login', $login->value());
    }

    public function test_it_trims_and_normalizes_login(): void
    {
        $login = Login::from('   USER-login   ');
        $this->assertSame('user-login', $login->value());
    }

    public function test_it_compares_logins_by_normalized_value(): void
    {
        $login = Login::from('USER-login');
        $sameLogin = Login::from(' user-login ');
        $anotherLogin = Login::from('another-login');

        $this->assertTrue($login->equals($sameLogin));
        $this->assertFalse($login->equals($anotherLogin));
    }
}
