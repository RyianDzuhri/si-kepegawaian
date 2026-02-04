@extends('layouts.auth')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary">Login</button>
    </div>
</form>

@endsection
