@extends('layouts.app')

@section('page_title','Edit Customer')

@section('content')
<div class="container-fluid" style="max-width:600px;">
  <div class="card p-4">
    <h5 class="mb-3">Edit Customer</h5>

    @if($errors->any())
      <div class="alert alert-danger py-2">
        {{ $errors->first() }}
      </div>
    @endif

    <form action="{{ route('customers.update', $customer->id_customer) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label">Nama Customer</label>
        <input type="text" name="nama_customer" class="form-control"
               value="{{ old('nama_customer', $customer->nama_customer) }}" required>
      </div>

      <div class="mb-3">
        <label class="form-label">No HP</label>
        <input type="text" name="no_hp" class="form-control"
               value="{{ old('no_hp', $customer->no_hp) }}">
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control"
               value="{{ old('email', $customer->email) }}">
      </div>

      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('customers.index') }}" class="btn btn-light">Batal</a>
        <button class="btn btn-primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>
@endsection
