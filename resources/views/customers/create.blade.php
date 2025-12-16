@extends('layouts.app')

@section('page_title','Tambah Customer')

@section('content')
<div class="container-fluid" style="max-width:600px;">
  <div class="card p-4">
    <h5 class="mb-3">Tambah Customer</h5>

    @if($errors->any())
      <div class="alert alert-danger py-2">
        {{ $errors->first() }}
      </div>
    @endif

    <form action="{{ route('customers.store') }}" method="POST">
      @csrf

      <div class="mb-3">
        <label class="form-label">Nama Customer</label>
        <input type="text" name="nama_customer" class="form-control"
               value="{{ old('nama_customer') }}" required>
      </div>

      <div class="mb-3">
        <label class="form-label">No HP</label>
        <input type="text" name="no_hp" class="form-control"
               value="{{ old('no_hp') }}">
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control"
               value="{{ old('email') }}">
      </div>

      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('customers.index') }}" class="btn btn-light">Batal</a>
        <button class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection
