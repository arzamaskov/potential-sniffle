@extends('layouts.guest')

@section('content')
    <div class="min-h-full bg-[#f7f7f4]">
        @include('layouts.partials.app-header', ['user' => $user])

        <main class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="max-w-3xl">
                    <h1 class="text-3xl font-semibold tracking-tight text-gray-950">Редактирование профиля</h1>
                    <p class="mt-3 text-sm leading-6 text-gray-600">
                        Укажите параметры спортсмена для будущих расчетов зон и аналитики.
                    </p>
                </div>
            </div>

            <form action="#" method="POST" class="space-y-3">
                @csrf

                <div class="grid gap-4 lg:grid-cols-[minmax(0,1.25fr)_minmax(320px,0.75fr)]">
                    <section class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                        <div class="mb-5">
                            <h2 class="text-base font-semibold text-gray-950">Параметры спортсмена</h2>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="age" class="block text-xs font-medium text-gray-600">Возраст</label>
                                <input
                                    id="age"
                                    name="age"
                                    type="number"
                                    min="1"
                                    max="120"
                                    inputmode="numeric"
                                    class="mt-1.5 block h-10 w-full rounded-md border border-gray-200 px-3 text-sm text-gray-950 shadow-sm transition-colors focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900"
                                >
                                <p class="mt-1 text-xs text-gray-400">1-120</p>
                            </div>

                            <div>
                                <label for="sex" class="block text-xs font-medium text-gray-600">Пол</label>
                                <select
                                    id="sex"
                                    name="sex"
                                    class="mt-1.5 block h-10 w-full rounded-md border border-gray-200 bg-white px-3 text-sm text-gray-950 shadow-sm transition-colors focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900"
                                >
                                    <option value="">Не выбран</option>
                                    <option value="male">Мужской</option>
                                    <option value="female">Женский</option>
                                </select>
                            </div>

                            <div>
                                <label for="height_cm" class="block text-xs font-medium text-gray-600">Рост, см</label>
                                <input
                                    id="height_cm"
                                    name="height_cm"
                                    type="number"
                                    min="1"
                                    max="250"
                                    inputmode="numeric"
                                    class="mt-1.5 block h-10 w-full rounded-md border border-gray-200 px-3 text-sm text-gray-950 shadow-sm transition-colors focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900"
                                >
                                <p class="mt-1 text-xs text-gray-400">100-250 см</p>
                            </div>

                            <div>
                                <label for="weight_kg" class="block text-xs font-medium text-gray-600">Вес, кг</label>
                                <input
                                    id="weight_kg"
                                    name="weight_kg"
                                    type="number"
                                    min="1"
                                    max="300"
                                    step="0.1"
                                    inputmode="decimal"
                                    class="mt-1.5 block h-10 w-full rounded-md border border-gray-200 px-3 text-sm text-gray-950 shadow-sm transition-colors focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900"
                                >
                                <p class="mt-1 text-xs text-gray-400">30-250 кг</p>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="shoe_size_eu" class="block text-xs font-medium text-gray-600">
                                    Размер обуви
                                </label>
                                <input
                                    id="shoe_size_eu"
                                    name="shoe_size_eu"
                                    type="number"
                                    min="1"
                                    max="60"
                                    step="0.5"
                                    inputmode="decimal"
                                    class="mt-1.5 block h-10 w-full rounded-md border border-gray-200 px-3 text-sm text-gray-950 shadow-sm transition-colors focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900"
                                >
                                <p class="mt-1 text-xs text-gray-400">Например: 42.5</p>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                        <div class="mb-5 flex items-baseline justify-between gap-4">
                            <h2 class="text-base font-semibold text-gray-950">Пульсовые настройки</h2>
                            <span class="text-xs font-medium text-gray-400">уд/мин</span>
                        </div>

                        <div class="grid gap-4">
                            <div>
                                <label for="max_heart_rate" class="block text-xs font-medium text-gray-600">
                                    Максимальный пульс
                                </label>
                                <input
                                    id="max_heart_rate"
                                    name="max_heart_rate"
                                    type="number"
                                    min="1"
                                    max="250"
                                    inputmode="numeric"
                                    class="mt-1.5 block h-10 w-full rounded-md border border-gray-200 px-3 text-sm text-gray-950 shadow-sm transition-colors focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900"
                                >
                                <p class="mt-1 text-xs text-gray-400">Например: 190</p>
                            </div>

                            <div>
                                <label for="resting_heart_rate" class="block text-xs font-medium text-gray-600">
                                    Пульс покоя
                                </label>
                                <input
                                    id="resting_heart_rate"
                                    name="resting_heart_rate"
                                    type="number"
                                    min="1"
                                    max="200"
                                    inputmode="numeric"
                                    class="mt-1.5 block h-10 w-full rounded-md border border-gray-200 px-3 text-sm text-gray-950 shadow-sm transition-colors focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900"
                                >
                                <p class="mt-1 text-xs text-gray-400">Например: 48</p>
                            </div>

                            <div>
                                <label for="threshold_heart_rate" class="block text-xs font-medium text-gray-600">
                                    Пороговый пульс / ПАНО
                                </label>
                                <input
                                    id="threshold_heart_rate"
                                    name="threshold_heart_rate"
                                    type="number"
                                    min="1"
                                    max="250"
                                    inputmode="numeric"
                                    class="mt-1.5 block h-10 w-full rounded-md border border-gray-200 px-3 text-sm text-gray-950 shadow-sm transition-colors focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900"
                                >
                                <p class="mt-1 text-xs leading-5 text-gray-400">
                                    Например: 168. Используется для расчета тренировочных зон.
                                </p>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <a
                        href="{{ route('profile', absolute: false) }}"
                        class="inline-flex min-h-10 items-center justify-center rounded-md border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition-colors hover:border-gray-300 hover:bg-gray-50 hover:text-gray-950 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
                    >
                        Отмена
                    </a>

                    <button
                        type="submit"
                        class="inline-flex min-h-10 items-center justify-center rounded-md border border-transparent bg-[#111827] px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-black focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
                    >
                        Сохранить
                    </button>
                </div>
            </form>
        </main>
    </div>
@endsection
