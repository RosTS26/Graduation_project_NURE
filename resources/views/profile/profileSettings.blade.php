@extends('layouts.app')

@section('content')
<form action="{{ route('profile.passwordUpdate') }}" method="POST" class="">
    @csrf
    @method('patch')
    <div class="mb-4 fs-5">
        <label for="titel" class="form-label mb-4"><b>Change password</b></label>
        <p>The password must contain at least 8 characters, including numbers, letters and special characters (!@$%).</p>
    </div>
    <div class="row mb-3">
        <div class="col-sm-2">
            <p>Current password</p>
        </div>
        <div class="col-sm-5">
            <input type="password" name="currentPassword" class="form-control @error('currentPassword') is-invalid @enderror">
            @error('currentPassword')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-sm-2">
            <p>New password</p>
        </div>
        <div class="col-sm-5">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-sm-2">
            <p>Confirm password</p>
        </div>
        <div class="col-sm-5">
            <input type="password" name="password_confirmation" class="form-control">
        </div>
    </div>
    <button type="submit" class="btn btn-primary mb-3">Change password</button>
</form>
@if (session('success'))
<div class="row d-flex">
    <div class="p-2 col-sm-3 alert alert-success text-center" role="alert">
        <p class="m-0">{{ session('success') }}</p>
    </div>
</div>
@endif
@if (session('error'))
<div class="row d-flex">
    <div class="p-2 col-sm-3 alert alert-warning text-center" role="alert">
        <p class="m-0">{{ session('error') }}</p>
    </div>
</div>
@endif
<hr>
<form action="{{ route('profile.delete') }}" method="POST" class="">
    @csrf
    @method('delete')
    <div class="mb-4 fs-5">
        <label for="titel" class="form-label mb-4"><b>Delete account</b></label>
        <p>You can delete your account while losing access to it.<br>
        Enter your password and click the "Delete" button to perform the action.</p>
    </div>
    <div class="row mb-3">
        <div class="col-sm-2">
            <p>Enter password</p>
        </div>
        <div class="col-sm-5">
            <input type="password" name="password" class="form-control @error('deleteAcc') is-invalid @enderror">
            @error('deleteAcc')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <button type="submit" class="btn btn-danger mb-3">Delete account</button>
</form>
<hr>
@endsection