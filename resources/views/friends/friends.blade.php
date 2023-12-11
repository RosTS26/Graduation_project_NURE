@extends('layouts.app')

@section('content')
<div class="friends-panel">
    <div class="btns-block">
        <input type="radio" name="friends" id="option1" checked="true">
        <label for="option1" class="radio" id="my-friends"><b>My friends</b></label>
        <input type="radio" name="friends" id="option2">
        <label for="option2" class="radio" id="sent-app"><b>Sent app</b></label>
        <input type="radio" name="friends" id="option3">
        <label for="option3" class="radio" id="incom-app">
            <b>Imcoming app</b>
            @if ($numIncomApp > 0)
            <span class="numIncom position-absolute translate-middle badge rounded-pill bg-danger">{{ $numIncomApp }}</span>
            @elseif ($numIncomApp > 99)
            <span class="numIncom position-absolute translate-middle badge rounded-pill bg-danger">99+</span>
            @else
            <span class="numIncom position-absolute translate-middle badge rounded-pill bg-danger" style="display: none">0</span>
            @endif
        </label>
        <input type="radio" name="friends" id="option4">
        <label for="option4" class="radio" id="find-friend"><b>Find friends</b></label>
    </div>
    <div class="find-panel input-group ">
        <input type="text" class="form-control" placeholder="Search for friends">
        <button class="btn btn-secondary search-btn" type="button" id="search-friend">
            <svg fill="none" height="16" viewBox="0 0 16 16" width="16" xmlns="http://www.w3.org/2000/svg">
                <path d="M6.5 1a5.5 5.5 0 0 1 4.38 8.82l3.9 3.9a.75.75 0 0 1-1.06 1.06l-3.9-3.9A5.5 5.5 0 1 1 6.5 1zm0 1.5a4 4 0 1 0 0 8 4 4 0 0 0 0-8z" fill="currentColor"></path>
            </svg>
        </button>
    </div>
    <div class="friends-info">
        <ul class="friends-list">
            @if (count($friendsData) == 0)
            <p class="not-friends">You have no friends :(<br>Send a friend request in the <b>"sent app"</b> tab</p>
            @endif
            @foreach ($friendsData as $user)
            <li class='user-item' id="{{ $user['id'] }}">
                <div class="avatar-block">
                    <a href="/friends/{{ $user['id'] }}">
                        <img class="avatar" src="{{ $user['avatar'] }}">
                    </a>
                    <span style="display: none;" id="{{ $user['id'] }}" class="check-online position-absolute translate-middle badge rounded-pill"> </span>
                </div>
                <div class='user-info'>
                    <p><b>{{ $user['name'] }}</b> (id: {{ $user['id'] }})</p>
                    <div class="button-block-{{ $user['id'] }}">
                        <a href="/messenger?id={{ $user['id'] }}" class="btn-user-info send-msg" data-user-id="{{ $user['id'] }}">Send a message</a><button class="btn-user-info delete-friend" data-user-id="{{ $user['id'] }}">Delete friend</button>
                    </div>
                </div>
            </li>
            <hr>
            @endforeach
        </ul>
    </div>
</div>
@endsection
@push('css')
<link rel="stylesheet" href="{{ asset('css/friends/friends.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('/js/friends/friends.js') }}" defer></script>
@endpush