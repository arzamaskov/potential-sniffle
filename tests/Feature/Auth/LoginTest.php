<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_shows_login_form(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('action="'.route('login').'"', false);
        $response->assertSee('method="POST"', false);
        $response->assertSee('name="login"', false);
        $response->assertSee('name="password"', false);
    }

    public function test_it_authenticates_user_with_valid_credentials(): void
    {
        $user = $this->user();

        $response = $this
            ->withSession(['_token' => 'test-token'])
            ->post('/login', [
                '_token' => 'test-token',
                'login' => ' USER-login ',
                'password' => 'secret-password',
            ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_it_rejects_invalid_credentials(): void
    {
        $this->user();

        $response = $this
            ->from('/')
            ->withSession(['_token' => 'test-token'])
            ->post('/login', [
                '_token' => 'test-token',
                'login' => 'user-login',
                'password' => 'wrong-password',
            ]);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors([
            'login' => 'Invalid credentials',
        ]);
        $this->assertGuest();
    }

    public function test_it_shows_invalid_credentials_error_after_failed_login(): void
    {
        $this->user();

        $response = $this
            ->followingRedirects()
            ->from('/')
            ->withSession(['_token' => 'test-token'])
            ->post('/login', [
                '_token' => 'test-token',
                'login' => 'user-login',
                'password' => 'wrong-password',
            ]);

        $response->assertOk();
        $response->assertSee('Invalid credentials');
        $this->assertGuest();
    }

    private function user(): User
    {
        return User::query()->create([
            'id' => '01HX8F5X4B9Z7N6Y2K3M4P5Q6R',
            'login' => 'user-login',
            'password_hash' => Hash::make('secret-password'),
        ]);
    }
}
