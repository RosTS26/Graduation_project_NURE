@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center">
    <canvas id="game" width="600px" height="600px"></canvas>
</div>
<div class="btn-start d-flex justify-content-center">
    <input type="button" value="Start" id="btnStart" class="btn btn-primary fs-4">
</div>
<div class="gamepad">
    <div class="top-btn">
        <button id="btn-up" class="btn btn-primary fs-4">&#11014;</button>
    </div>
    <div class="bottom-btns">
        <button id="btn-left" class="btn btn-primary fs-4">&#11013;</button>
        <button id="btn-down" class="btn btn-primary fs-4">&#11015;</button>
        <button id="btn-right" class="btn btn-primary fs-4">&#10145;</button>
    </div>
</div>
@endsection
@push('css')
<link rel="stylesheet" href="{{ asset('css/games/snake/snake.css') }}">
@endpush
@push('scripts')
<script>
	var token = "{{ $token }}";
</script>
<script src="{{ asset('/js/games/snake/snake.js') }}"></script>
@endpush