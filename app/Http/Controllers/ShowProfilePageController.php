<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;

final class ShowProfilePageController
{
    public function __invoke(): View
    {
        return view('auth.profile', [
            'user' => auth()->user(),
        ]);
    }
}
