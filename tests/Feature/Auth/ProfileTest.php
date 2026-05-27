<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_home(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_is_redirected_to_profile_from_home(): void
    {
        $response = $this->actingAs($this->user())->get('/');

        $response->assertRedirect('/profile');
    }

    public function test_guest_is_redirected_to_login_from_profile(): void
    {
        $response = $this->get('/profile');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_see_profile_page(): void
    {
        $user = $this->user();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertOk();
        $response->assertSee('RunTracker');
        $response->assertSee('href="/workouts"', false);
        $response->assertSee('Тренировки');
        $response->assertSee('href="/profile"', false);
        $response->assertSee('Профиль спортсмена');
        $response->assertSee('Настройки, которые используются для расчета зон, темпа и анализа тренировок.');
        $response->assertSee('Логин');
        $response->assertSee('user-login');
        $response->assertSee('Параметры спортсмена');
        $response->assertSee('Возраст');
        $response->assertSee('Пол');
        $response->assertSee('Рост, см');
        $response->assertSee('Вес, кг');
        $response->assertDontSee('Размер обуви');
        $response->assertSee('Пульсовые настройки');
        $response->assertSee('Максимальный пульс, уд/мин');
        $response->assertSee('Пульс покоя, уд/мин');
        $response->assertSee('Пороговый пульс / ПАНО');
        $response->assertSee('не указан');
        $response->assertSee('Редактировать профиль');
        $response->assertSee('href="/profile/edit"', false);
        $response->assertDontSee('Мои тренировки');
        $response->assertDontSee('Добавить тренировку');
        $response->assertSee('action="/logout"', false);
        $response->assertSee('method="POST"', false);
        $response->assertSee('Выйти');
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = $this->user();

        $response = $this
            ->actingAs($user)
            ->withSession(['_token' => 'test-token'])
            ->post('/logout', [
                '_token' => 'test-token',
            ]);

        $response->assertRedirect('/login');
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
