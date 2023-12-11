@extends('layouts.admin')

@section('panel-info')
<h1 class="m-0">Users panel</h1>
@endsection

@section('users-list')
<li class="user-item">{{ $user->name }} (id: {{ $user->id }})</li>
@endsection

@section('content')
<div class="row card-block">
    <div class="card-body col-sm-4">
        <div class="avatar">
            <h3>Profile</h3>
            <hr>
            <img src="{{ asset($user->avatar) }}" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
        </div>
        <div class="row">
            <div class="col-sm-3">
                <p class="text-muted mb-0">ID:</p>
            </div>
            <div class="col-sm-9">
                <p class="mb-0">{{ $user->id }}</p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-3">
                <p class="text-muted mb-0">Name:</p>
            </div>
            <div class="col-sm-9">
                <p class="mb-0">{{ $user->name }}</p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-3">
                <p class="text-muted mb-0">Email:</p>
            </div>
            <div class="col-sm-9">
                <p class="mb-0">{{ $user->email }}</p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-5">
                <p class="text-muted mb-0">Registration:</p>
            </div>
            <div class="col-sm-7">
                <p class="mb-0">{{ $user->created_at }}</p>
            </div>
        </div>
    </div>
    <div class="user-ban card-body col-lg-6">
        <h3 class="ban-or-block">Ban/unban a user</h3>
        <hr>
        <div class="choice-radio">
            <input type="radio" name="radio" id="ban-acc">
            <label for="ban-acc" id="styles-ban-acc">Ban/Unban account</label>
            <input type="radio" name="radio" id="block-chat">
            <label for="block-chat" id="styles-block-chat">Block/Unblock chat</label>
        </div>
        <div class="action-bar">
            <div class="element-first">
                <p>Reason for ban: </p>
                <textarea id="cause" placeholder="Enter text..."></textarea>
            </div>
            <div class="element">
                <p>Amount of days: </p>
                <input type="number" autocomplete="off" id="days" placeholder="Enter day(s)">
            </div>
            <input type="button" class="changeBtn" value="Ban" id="btnBan">
            <div class="checkbox">
                <input type="checkbox" id="checkUnban">
                <label for="checkUnban">Unban user</label>
            </div>
            <div class="res" id="banMsg"></div>
        </div>
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('css/admin/adminUsers.css') }}">
@endpush

@push('scripts')
<script>
    var csrf = "{{ csrf_token() }}";
    var user_id = "{{ $user->id }}";
</script>
<script src="{{ asset('/js/admin/adminUsers.js') }}"></script>
@endpush