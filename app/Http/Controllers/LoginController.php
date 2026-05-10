<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Src\Identity\Application\User\AuthenticateUserCommand;
use Src\Identity\Application\User\AuthenticateUserHandler;
use Src\Identity\Application\User\InvalidCredentials;

final class LoginController
{
    public function __invoke(Request $request, AuthenticateUserHandler $handler): RedirectResponse
    {
        $login = (string) $request->input('login');
        $password = (string) $request->input('password');
        $command = new AuthenticateUserCommand($login, $password);
        try {
            $userId = $handler->handle($command);
            Auth::guard('web')->loginUsingId($userId->value());
            $request->session()->regenerate();

            return redirect()->route('home');
        } catch (InvalidCredentials $e) {
            return back()->withErrors(['login' => 'Invalid credentials'])->withInput(['login' => $login]);
        }
    }
}
