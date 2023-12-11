@extends('layouts.app')

@section('content')
<section style="background-color: #eee;">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <h3>User profile</h3>
                        <hr>
                        <div class="avatar-block">
                            <img src="{{ $user->avatar }}" alt="avatar"
                            class="rounded-circle img-fluid" style="width: 150px;">
                            <span style="display: none;" id="{{ $user['id'] }}" class="check-online position-absolute translate-middle badge rounded-pill"> </span>
                        </div>
                        <h4 class="my-3">{{ $user->name }}</h4>
                        <div class="button-block d-flex justify-content-center mb-2">
                            @if ($status == 0)
                            <button class="btn btn-outline-primary me-2 add-friend" data-user-id="{{ $user->id }}">Add as friend</button>
                            @elseif ($status == 1)
                            <a href="/messenger?id={{ $user->id }}" class="btn btn-outline-primary me-2 send-msg" data-user-id="{{ $user->id }}">Send a message</a>
                            <button class="btn btn-outline-warning delete-friend" data-user-id="{{ $user->id }}">Delete friend</button>
                            @elseif ($status == 2)
                            <button class="btn btn-outline-primary me-2 cancel-app" data-user-id="{{ $user->id }}">Cancel the application</button>
                            @elseif ($status == 3)
                            <button class="btn btn-outline-success me-2 add-friend" data-user-id="{{ $user->id }}">Accept</button>
                            <button class="btn btn-outline-warning reject-app" data-user-id="{{ $user->id }}">Reject</button>
                            @endif
                        </div>
                        <div class="msg-info"></div>
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
                                                <p class="text-muted mb-0"><b>{{ $user->snake->top_score }}</b></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row ms-1">
                                            <div class="col-sm-7">
                                                <p class="mb-0">Num of games:</p>
                                            </div>
                                            <div class="col-sm-5">
                                                <p class="text-muted mb-0"><b>{{ $user->snake->num_of_games }}</b></p>
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
                                                <p class="text-muted mb-0"><b>{{ $user->tetris->top_score }}</b></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row ms-1">
                                            <div class="col-sm-7">
                                                <p class="mb-0">Num of games:</p>
                                            </div>
                                            <div class="col-sm-5">
                                                <p class="text-muted mb-0"><b>{{ $user->tetris->num_of_games }}</b></p>
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
                                                <p class="text-muted mb-0"><b>{{ $user->roulette->deposit }}</b></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row ms-1">
                                            <div class="col-sm-7">
                                                <p class="mb-0">Num of games:</p>
                                            </div>
                                            <div class="col-sm-5">
                                                <p class="text-muted mb-0"><b>{{ $user->roulette->num_of_games }}</b></p>
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
                                <p class="text-muted mb-0" id="user-id">{{ $user->id }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Name</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $user->name }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Email</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $user->email }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Registration</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $user->created_at }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('css/profile/profile.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('/js/friends/friendsShow.js') }}"></script>
@endpush