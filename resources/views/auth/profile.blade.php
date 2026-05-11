@extends('layouts.guest')

@section('content')
    <h1>Профиль</h1>

    <p>{{ $user->login }}</p>

    <form action="/logout" method="POST">
        @csrf

        <button type="submit">Выйти</button>
    </form>
@endsection
