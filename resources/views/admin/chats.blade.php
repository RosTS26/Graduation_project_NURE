@extends('layouts.admin')

@section('panel-info')
<h1 class="m-0">Chats panel</h1>
@endsection

@section('users-list')
@foreach ($processedData as $user)
<li class='user-item' id="{{ $user['user_id'] }}">
    @if ($user['numNewMsgs'] > 0)
    <span class="check-newMsg user-{{ $user['user_id'] }} fs-6 position-absolute translate-middle badge rounded-pill bg-danger">
        @if ($user['numNewMsgs'] > 99)
        99+
        @else
        {{ $user['numNewMsgs'] }}
        @endif
    </span>
    @else
    <span style="display: none;" class="check-newMsg user-{{ $user['user_id'] }} fs-6 position-absolute translate-middle badge rounded-pill bg-danger">0</span>
    @endif
    <div class='user-name-id'>{{ $user['username'] }} (id: {{ $user['user_id'] }})</div>
</li>
@endforeach
@endsection

@section('content')
<div class="field-for-message">
    <div class="chat-container">
    </div>
    <div class="text-and-btn">
        <textarea id="text-to-send" placeholder="Send message..." disabled></textarea>
        <button class="btn" value="Send" id="btnSendMsg" disabled>
            <img class="send-button" src="{{ asset('/images/admin/icon/send-button.png') }}" alt="send-button">
        </button>
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('css/admin/adminChats.css') }}">
@endpush

@push('scripts')
<script>
    var csrf = "{{ csrf_token() }}";
</script>
<script src="{{ asset('/js/admin/adminChats.js') }}"></script>
@endpush