<header class="border-b border-gray-200 bg-white">
    <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-2 text-gray-900">
            @include('auth.partials.brand-mark')
        </div>

        <div class="flex items-center gap-5">
            <a
                href="/workouts"
                class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-950 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
            >
                Тренировки
            </a>
            <span class="text-sm font-medium text-gray-700">{{ $user->login }}</span>

            <form action="/logout" method="POST">
                @csrf

                <button
                    type="submit"
                    class="cursor-pointer text-sm font-medium text-gray-500 transition-colors hover:text-gray-950 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
                >
                    Выйти
                </button>
            </form>
        </div>
    </div>
</header>
