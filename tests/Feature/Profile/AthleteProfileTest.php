<?php

declare(strict_types=1);

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AthleteProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_profile_edit_page(): void
    {
        $response = $this->get('/profile/edit');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_see_profile_edit_form(): void
    {
        $response = $this->actingAs($this->user())->get('/profile/edit');

        $response->assertOk();
        $response->assertSee('Редактирование профиля');
        $response->assertSee('name="age"', false);
        $response->assertSee('name="sex"', false);
        $response->assertSee('name="height_cm"', false);
        $response->assertSee('name="weight_kg"', false);
        $response->assertSee('name="shoe_size_eu"', false);
        $response->assertSee('name="max_heart_rate"', false);
        $response->assertSee('name="resting_heart_rate"', false);
        $response->assertSee('name="threshold_heart_rate"', false);
        $response->assertSee('Не выбран');
        $response->assertSee('Размер обуви');
        $response->assertSee('Например: 42.5');
        $response->assertSee('Пороговый пульс / ПАНО');
        $response->assertSee('Используется для расчета тренировочных зон.');
        $response->assertSee('Сохранить');
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
