@extends('layouts.app')

@section('content')
<div class="row row-cols-1 row-cols-md-5 g-4">
    <div class="col">
        <a href="{{ route('game.snake.index') }}">
            <img src="{{ asset('images/games/snake/snake.jpg') }}" class="card-img-top rounded" alt="...">
        </a>
    </div>
    <div class="col">
        <a href="{{ route('game.tetris.index') }}">
            <img src="{{ asset('images/games/tetris/tetris.jpg') }}" class="card-img-top rounded" alt="...">
        </a>
    </div>
    <div class="col">
        <a href="{{ route('game.roulette.index') }}">
            <img src="{{ asset('images/games/roulette/roulette.jpg') }}" class="card-img-top rounded" alt="...">
        </a>
    </div>
    <div class="col">
        <a href="{{ route('game.seabattle.index') }}">
            <img src="{{ asset('images/games/seaBattle/sea-battle.png') }}" class="card-img-top rounded" alt="...">
        </a>
    </div>
</div>
@endsection
