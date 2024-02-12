@extends('layouts.app')

@section('content')
<section style="background-color: #eee;">
    <div class="chat-panel container">
        <div class="info-chat-container">
            <div class="info-container">
                <button class="toggle-button">&#10149;</button>
                <div class="user-info">
                    <div class="find-user input-group">
                        <input id="search-text" class="form-control form-control-sidebar" type="text" placeholder="Search user" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar" id="btn-search">&#128269;</button>
                        </div>
                    </div>
                    <ul class="user-list">
                        @if (count($friends) == 0)
                        <p class="not-friends">
                            You don't have any chats available!<br>
                            <a href="/friends">find friends</a>
                        </p>
                        @endif
                        @foreach ($friends as $user)
                        <li class='user-item' id="{{ $user['id'] }}">
                            <div class="avatar-block">
                                <img class="avatar" src="{{ $user['avatar'] }}" alt="avatar">
                                <span style="display: none;" id="{{ $user['id'] }}" class="check-online position-absolute translate-middle badge rounded-pill"> </span>
                            </div>
                            <div class='user-data'>
                                <p class="username">{{ $user['name'] }}</p>
                                <p class="last-msg">{{ $user['lastMsg'] }}</p>
                            </div>
                            @if ($user['numNewMsgs'] > 0)
                            <span class="check-newMsg newMsg-info-{{ $user['id'] }} position-absolute translate-middle badge rounded-pill bg-danger">
                                @if ($user['numNewMsgs'] > 99)
                                99+
                                @else
                                {{ $user['numNewMsgs'] }}
                                @endif
                            </span>
                            @else
                            <span style="display: none;" class="check-newMsg newMsg-info-{{ $user['id'] }} position-absolute translate-middle badge rounded-pill bg-danger">
                            @endif
                        </li>
                        @endforeach
                        <li class="loading">
                            <img src="{{ asset('/images/loading.gif') }}" alt="loading">
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Chat conteiner -->
            <div class="chat-container">
                <div class="user-info-panel"></div>
                <div class="chat-list">
                    @if ($blockChat)
                    <div class="chat-info bg-warning">
                        You are blocked from chats!<br><br>
                        from: {{ $blockChat['start_date'] }}<br>
                        to: {{ $blockChat['end_date'] }}<br><br>
                        Cause: "{{ $blockChat['cause'] }}"    
                    </div>
                    @else
                        @if (!$user_id)
                        <div class="chat-info">Select, who you would like to write to!</div>
                        @endif
                    @endif
                </div>
                <div class="text-and-btn">
                    <textarea id="text-to-send" placeholder=@if ($blockChat) "Message sending is blocked" disabled @else "Send message..." @endif></textarea>
                    <button class="btn" value="Send" id="btnSendMsg" disabled>
                        <img class="send-button" src="{{ asset('/images/admin/icon/send-button.png') }}" alt="send-button">
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('css/messenger/messenger.css') }}">
@endpush

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script>
    var myId = "{{ auth()->user()->id }}";
</script>
<script src="/js/messenger/messenger.js" defer></script>
@if ($user_id)
<script>
    userChatId = Number('{{ $user_id }}');
</script>
<script src="/js/messenger/firstLoad.js" defer></script>
@endif

@endpush