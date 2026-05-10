@extends('layouts.guest')

@section('content')
    <div class="flex min-h-full">
        <!-- Left half: Image -->
        <div class="relative hidden w-0 flex-1 lg:block">
            <img class="absolute inset-0 h-full w-full object-cover" src="{{ asset('images/runner.png') }}" alt="Running">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-gray-900/20 to-transparent"></div>

            <!-- Logo -->
            <div class="absolute top-10 left-12 flex items-center gap-2 text-white">
                @include('auth.partials.brand-mark')
            </div>

            <!-- Bottom Text -->
            <div class="absolute bottom-12 left-12 text-white">
                <h2 class="text-3xl font-bold tracking-tight mb-2">One run<br>at a time.</h2>
                <p class="text-gray-300 font-medium text-lg">Make it count.</p>
            </div>
        </div>

        <!-- Right half: Form -->
        <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-32">
            <div class="mx-auto w-full max-w-sm lg:w-96">

                <!-- Mobile Logo (hidden on desktop) -->
                <div class="flex items-center gap-2 text-gray-900 lg:hidden mb-8">
                    @include('auth.partials.brand-mark')
                </div>

                <div class="text-center lg:text-left mb-10">
                    <h2 class="text-[26px] font-semibold tracking-tight text-gray-900">С возвращением</h2>
                    <p class="mt-2 text-[15px] text-gray-500">Войдите, чтобы продолжить</p>
                </div>

                <div class="mt-8">
                    @include('auth.partials.login-form')
                </div>
            </div>
        </div>
    </div>
@endsection
