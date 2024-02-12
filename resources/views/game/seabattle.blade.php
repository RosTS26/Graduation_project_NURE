@extends('layouts.app')

@section('content')
<div class="search">
    <div class="gif-box"></div>
    <p class="text-search">Game search...</p>
    <!-- <div class="button-block"></div> -->
</div>
<div class="game">
    <div class="field1">
        <div class="view-y"></div>
        <div class="row1">
            <div class="view-x"></div>
            <div class="sea-battle my-field"></div>
        </div>
    </div>
    <div class="field2">
        <div class="view-y"></div>
        <div class="row1">
            <div class="view-x"></div>
            <div class="sea-battle his-field"></div>
        </div>
    </div>
    <div class="menu">
        <div class="ships-block">
            <div>Available ships</div>
            <div class="ship">
                <input type="radio" name="ship-check" id="ship-4" class="btn-ship">
                <label for="ship-4"></label>
                <p class="quantity-text quantity-4">1x: </p>
                <div class="view-ship">
                    <div class="element-ship"></div>
                    <div class="element-ship"></div>
                    <div class="element-ship"></div>
                    <div class="element-ship"></div>
                </div>
            </div>
            <div class="ship">
                <input type="radio" name="ship-check" id="ship-3" class="btn-ship">
                <label for="ship-3"></label>
                <p class="quantity-text quantity-3">2x: </p>
                <div class="view-ship">
                    <div class="element-ship"></div>
                    <div class="element-ship"></div>
                    <div class="element-ship"></div>
                </div>
            </div>
            <div class="ship">
                <input type="radio" name="ship-check" id="ship-2" class="btn-ship">
                <label for="ship-2"></label>
                <p class="quantity-text quantity-2">3x: </p>
                <div class="view-ship">
                    <div class="element-ship"></div>
                    <div class="element-ship"></div>
                </div>
            </div>
            <div class="ship">
                <input type="radio" name="ship-check" id="ship-1" class="btn-ship">
                <label for="ship-1"></label>
                <p class="quantity-text quantity-1">4x: </p>
                <div class="view-ship">
                    <div class="element-ship"></div>
                </div>
            </div>
            <div class="timer-ship">Timer&nbsp;<div class="timer-value">1:00</div></div>
            <div class="btns-block">
                <button id="rotate">Rotate</button>
                <button id="clear-field">Clear</button>
                <button id="random-field">Random</button>
            </div>
            <div class="btn-ready">
                <button id="ready">Ready</button>
            </div>
        </div>
    </div>
</div>
<div class="game-menu">
    <div class="my-game-info">
        <p>My field</p>
        <div class="timer-ship">Timer&nbsp;<div class="my-timer-game">10:00</div></div>
        <div class="destroy">Ships destroyed:&nbsp;<div class="my-destroy">0</div></div>
    </div>
    <div class="general-info">
        <p class="players-move"></p>
        <button id="give-up">Give up</button>
    </div>
    <div class='his-game-info'>
        <p>Enemy field</p>
        <div class="timer-ship">Timer&nbsp;<div class="his-timer-game">10:00</div></div>
        <div class="destroy">Ships destroyed:&nbsp;<div class="his-destroy">0</div></div>
    </div>
</div>
<div class="panel-info"></div>
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/games/seaBattle/seaBattleCSS.css') }}">
<style>
    .gif-box {
        background-image: url('{{ asset('images/games/seaBattle/loading.gif') }}');
    }
</style>
@endpush

@push('scripts')
<script>
    const myID = Number("{{ auth()->user()->id }}");
</script>
<!-- <script type="module" src="/js/games/seaBattle/seaBattle.js" defer></script> -->
<script type="module" src="/js/games/seaBattle/connection.js"></script>
@endpush