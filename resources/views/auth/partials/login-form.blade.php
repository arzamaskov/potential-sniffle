<form action="{{ route('login') }}" method="POST" class="space-y-6">
    @csrf

    <div>
        <label for="login" class="block text-xs font-medium text-gray-600 mb-1.5">Логин</label>
        <input
            type="text"
            name="login"
            id="login"
            required
            autocomplete="username"
            class="block w-full rounded-md border border-gray-200 px-3 py-2.5 text-gray-900 placeholder-gray-400 focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900 sm:text-sm transition-colors"
            placeholder="user name"
        >
        @error('login')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="block text-xs font-medium text-gray-600 mb-1.5">Пароль</label>
        <div class="relative">
            <input
                type="password"
                name="password"
                id="password"
                required
                class="block w-full rounded-md border border-gray-200 px-3 py-2.5 pr-10 text-gray-900 placeholder-gray-400 focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900 sm:text-sm transition-colors"
                placeholder="••••••••"
            >
            <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Запомнить меня -->
    <div class="flex items-center">
        <input
            id="remember-me"
            name="remember"
            type="checkbox"
            class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900"
        >
        <label for="remember-me" class="ml-2 block text-sm text-gray-600">
            Запомнить меня
        </label>
    </div>

    <div class="pt-2">
        <button
            type="submit"
            class="flex w-full justify-center rounded-md border border-transparent bg-[#111827] py-2.5 px-4 text-sm font-medium text-white shadow-sm hover:bg-black focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 transition-colors"
        >
            Войти
        </button>
    </div>
</form>
