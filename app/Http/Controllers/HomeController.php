<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

final class HomeController
{
    public function __invoke(): RedirectResponse
    {
        return Auth::check()
            ? redirect()->route('profile')
            : redirect()->route('login');
    }
}
