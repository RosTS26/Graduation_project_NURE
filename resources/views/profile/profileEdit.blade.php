@extends('layouts.app')

@section('content')
<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="row g-3">
    @csrf
    @method('patch')
    <div class="mb-3 fs-5">
        <label for="titel" class="form-label mb-4"><b>Change name</b></label>
        <div class="row">
            <div class="col-sm-2">
                <p>Current name:</p>
            </div>
            <div class="col-sm-4">
                <p class="text-muted">{{ $profile['name'] }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2">
                <p class="mb-0">New name:</p>
            </div>
            <div class="col-sm-4">
                <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Username">
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>
    <hr>
    <div class="mb-3 fs-5">
        <label for="titel" class="form-label mb-4"><b>Change email</b></label>
        <div class="row">
            <div class="col-sm-2">
                <p>Current email:</p>
            </div>
            <div class="col-sm-4">
                <p class="text-muted">{{ $profile['email'] }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2">
                <p class="mb-0">New email:</p>
            </div>
            <div class="col-sm-4">
                <input type="email" name='email' value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Email">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>
    <hr>
    <div class="mb-3 fs-5">
        <label for="titel" class="form-label mb-4"><b>New avatar</b></label>
        <div class="row">
            <div class="col-sm-2">
                <p class="mb-0">Download image:</p>
            </div>
            <div class="col-sm-4">
                <input type="file" name='avatar' class="form-control @error('avatar') is-invalid @enderror">
                @error('avatar')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>
    <hr>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Update</button>
        <a class="btn btn-outline-primary ms-3" href="{{ route('profile.index') }}">Back</a>
    </div>
</form>
@endsection