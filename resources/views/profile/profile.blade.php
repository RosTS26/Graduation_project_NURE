@extends('layouts.app')

@section('content')
<section style="background-color: #eee;">
    <div class="container" style="padding: 40px 13px">
        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <h3>My Profile</h3>
                        <hr>
                        <img src="{{ asset($profile['avatar']) }}" alt="avatar"
                        class="rounded-circle img-fluid" style="width: 150px;">
                        <h4 class="my-3">{{ $profile['name'] }}</h4>
                        <div class="d-flex justify-content-center mb-2">
                            <a class="btn btn-outline-primary me-2" href="{{ url('/profile/edit') }}">Edit</a>
                            <a class="btn btn-outline-primary me-2" href="{{ url('/profile/settings') }}">Settings</a>
                        </div>
                    </div>
                </div>
                <div class="card mb-4 mb-lg-0 fs-5">
                    <div class="card-body p-0">
                        <div class="accordion" id="accordionPanelsStayOpenExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="false" aria-controls="panelsStayOpen-collapseOne">
                                    <div class="fs-5">Snake progress</div>
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    <div class="row ms-1">
                                            <div class="col-sm-5">
                                                <p class="mb-0">Top score:</p>
                                            </div>
                                            <div class="col-sm-7">
                                                <p class="text-muted mb-0"><b>{{ $gamesInfo['snake']->top_score }}</b></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row ms-1">
                                            <div class="col-sm-7">
                                                <p class="mb-0">Num of games:</p>
                                            </div>
                                            <div class="col-sm-5">
                                                <p class="text-muted mb-0"><b>{{ $gamesInfo['snake']->num_of_games }}</b></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                                        <div class="fs-5">Tetris progress</div>
                                </button>
                                </h2>
                                <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        <div class="row ms-1">
                                            <div class="col-sm-5">
                                                <p class="mb-0">Top score:</p>
                                            </div>
                                            <div class="col-sm-7">
                                                <p class="text-muted mb-0"><b>{{ $gamesInfo['tetris']->top_score }}</b></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row ms-1">
                                            <div class="col-sm-7">
                                                <p class="mb-0">Num of games:</p>
                                            </div>
                                            <div class="col-sm-5">
                                                <p class="text-muted mb-0"><b>{{ $gamesInfo['tetris']->num_of_games }}</b></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                                        <div class="fs-5">Roulette progress</div>
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        <div class="row ms-1">
                                            <div class="col-sm-5">
                                                <p class="mb-0">Deposit:</p>
                                            </div>
                                            <div class="col-sm-7">
                                                <p class="text-muted mb-0"><b>{{ $gamesInfo['roulette']->deposit }}</b></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row ms-1">
                                            <div class="col-sm-7">
                                                <p class="mb-0">Num of games:</p>
                                            </div>
                                            <div class="col-sm-5">
                                                <p class="text-muted mb-0"><b>{{ $gamesInfo['roulette']->num_of_games }}</b></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 fs-5">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">ID</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $profile['id'] }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Name</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $profile['name'] }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Email</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $profile['email'] }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Registration</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $profile['create'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="chat-button">
    <input id="admin-chat" type="checkbox">
    <label for="admin-chat" id="label-admin-chat">
        <img class="chat-icon" src="{{ asset('/images/profile/icon/chat.png') }}" alt="Admin Chat Icon">
    </label>
    @if ($numNewMsgs > 0)
    <span class="check-newMsg fs-6 position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        @if ($numNewMsgs > 99)
        99+
        @else
        {{ $numNewMsgs }}
        @endif
    </span>
    @else
    <span style="display: none;" class="check-newMsg fs-6 position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
    @endif
</div>
<div class="admin-chat">
    <div class="chat-header">
        <p>Admin chat</p>
    </div>
    <div class="chat-container">
    <!-- messages -->
    </div>
    <div class="text-and-btn">
        <textarea id="text-to-send" placeholder="Enter message..."></textarea>
        <button class="btn" id="btnSendMsg">
            <img class="push-msg" src="{{ asset('/images/profile/icon/pushMsg.png') }}" alt="push msg">
        </button>
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('css/profile/profile.css') }}">
@endpush

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script>
    var csrf = "{{ csrf_token() }}";
    var numNewMsgs = "{{ $numNewMsgs }}";
    var userId = "{{ $profile['id'] }}";
</script>
<script src="{{ asset('/js/profile/profile.js') }}"></script>
@endpush