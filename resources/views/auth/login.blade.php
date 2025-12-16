@extends('layouts.app')

@section('content')
<div class="container" style="max-width:480px;margin-top:90px">
  <div class="card shadow-sm">
    <div class="card-body">
      <h4 class="card-title mb-3">Login Kasir WSI</h4>

      @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" value="{{ old('username') }}" required autofocus>
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>

        <div class="d-flex justify-content-between align-items-center">
          <button class="btn btn-primary">Masuk</button>
          <a href="#" class="text-muted">Lupa kata sandi?</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
