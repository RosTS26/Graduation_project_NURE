@extends('layouts.app')

@section('content')
<section>
    <div class="game">
        <div class="tetris-box">
            <div class="main"></div>
            <div id="msgInfo">Press "Start"!</div>
        </div>
        <div class="menu">
            <div id="nextFig">
                <p>Next figura</p>
                <img src="" id="img">
            </div>
            <input type="text" id="score" class="score" readonly>
            <input type="button" value="Start" id="btnStart" class="btn1">
            <input id="info" name="accordion_head" type="checkbox">
            <label for="info">Info</label>
            <div class="info_body">
                <h3>Control</h3>
                <p>Right - "D"</p>
                <p>Left - "A"</p>
                <p>Rotate - "W"</p>
                <p>Accelerated fall - "S"</p>
            </div>
        </div>
    </div>
    <div class="gamepad">
        <div class="top-btn">
            <button id="btn-rotate" class="btn btn-primary fs-4">&#8635;</button>
        </div>
        <div class="bottom-btns">
            <button id="btn-left" class="btn btn-primary fs-4">&#11013;</button>
            <button id="btn-down" class="btn btn-primary fs-4">&#11015;</button>
            <button id="btn-right" class="btn btn-primary fs-4">&#10145;</button>
        </div>
    </div>
</section>
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/games/tetris/tetrisCSS.css') }}">
@endpush

@push('scripts')
<script>
    var token = "{{ $token }}";
</script>
<script type="text/javascript" src="/js/games/tetris/tetrisScriptJQ.js" defer></script>
@endpush