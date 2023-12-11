@extends('layouts.admin')

@section('panel-info')
<h1 class="m-0">Users panel</h1>
@endsection

@section('users-list')
<li class="list-info">Enter username to select it</li>
@endsection

@section('content')
<div class="content-info">Select user for action...</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('css/admin/adminUsers.css') }}">
@endpush

@push('scripts')
<script>
    var csrf = "{{ csrf_token() }}";
</script>
<script src="{{ asset('/js/admin/adminUsers.js') }}"></script>
@endpush