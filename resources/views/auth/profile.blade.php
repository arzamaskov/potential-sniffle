@extends('layouts.guest')

@section('content')
    <div class="min-h-full bg-[#f7f7f4]">
        @include('layouts.partials.app-header', ['user' => $user])

        <main class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="max-w-3xl">
                    <h1 class="text-3xl font-semibold tracking-tight text-gray-950">Профиль спортсмена</h1>
                    <p class="mt-3 text-sm leading-6 text-gray-600">
                        Настройки, которые используются для расчета зон, темпа и анализа тренировок.
                    </p>
                    <p class="mt-4 text-sm text-gray-500">
                        Логин:
                        <span class="font-medium text-gray-950">{{ $user->login }}</span>
                    </p>
                </div>

                <a
                    href="{{ route('profile.edit', absolute: false) }}"
                    class="inline-flex min-h-10 shrink-0 items-center justify-center rounded-md border border-transparent bg-[#111827] px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-black focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
                >
                    Редактировать профиль
                </a>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <section class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <h2 class="text-base font-semibold text-gray-950">Параметры спортсмена</h2>
                    <dl class="mt-5 space-y-4 text-sm">
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-gray-500">Возраст</dt>
                            <dd class="text-gray-400">не указан</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-gray-500">Пол</dt>
                            <dd class="text-gray-400">не указан</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-gray-500">Рост, см</dt>
                            <dd class="text-gray-400">не указан</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-gray-500">Вес, кг</dt>
                            <dd class="text-gray-400">не указан</dd>
                        </div>
                    </dl>
                </section>

                <section class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <h2 class="text-base font-semibold text-gray-950">Пульсовые настройки</h2>
                    <dl class="mt-5 space-y-4 text-sm">
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-gray-500">Максимальный пульс, уд/мин</dt>
                            <dd class="text-gray-400">не указан</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-gray-500">Пульс покоя, уд/мин</dt>
                            <dd class="text-gray-400">не указан</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-gray-500">Пороговый пульс / ПАНО</dt>
                            <dd class="text-gray-400">не указан</dd>
                        </div>
                    </dl>
                </section>
            </div>
        </main>
    </div>
@endsection
