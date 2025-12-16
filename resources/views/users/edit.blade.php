@extends('layouts.app')

@section('page_title','Edit Pegawai')

@section('content')
<div class="container-fluid" style="max-width:720px;">
  <div class="card p-4">
    <h5>Edit Pegawai</h5>

    @if($errors->any())
      <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('users.update', $user->id_user) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label">Nama</label>
        <input name="nama_user" value="{{ old('nama_user', $user->nama_user) }}" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Username</label>
        <input name="username" value="{{ old('username', $user->username) }}" class="form-control" required>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Password (kosongkan jika tidak ingin ganti)</label>
          <input name="password" type="password" class="form-control">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Confirm Password</label>
          <input name="password_confirmation" type="password" class="form-control">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Role</label>
        <select name="role" class="form-select" required>
          <option value="pegawai" {{ $user->role=='pegawai' ? 'selected' : '' }}>Pegawai</option>
          <option value="manager" {{ $user->role=='manager' ? 'selected' : '' }}>Manager</option>
          <option value="owner" {{ $user->role=='owner' ? 'selected' : '' }}>Owner</option>
        </select>
      </div>

      <div class="d-flex gap-2">
        <a href="{{ route('users.index') }}" class="btn btn-light">Batal</a>
        <button class="btn btn-primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>
@endsection
