@extends('layouts.app')

@section('content')
<div class="indexBox">
	<canvas id="game" width="870px" height="630px"></canvas>
	<input type="button" value="Spin" id="btnSpin">
</div>
<div class="msgInfo">Load...</div>
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/games/roulette/rouletteCSS.css') }}">
@endpush

@push('scripts')
<script>
    var token = "{{ $token }}";
</script>
<script type="text/javascript" src="/js/games/roulette/rouletteScript.js" defer></script>
@endpush