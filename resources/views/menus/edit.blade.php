@extends('layouts.app')

@section('page_title','Edit Menu')

@section('content')
<div class="container-fluid" style="max-width:720px;">
  <div class="card p-4">
    <h5>Edit Menu</h5>

    @if($errors->any())
      <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('menus.update', $menu->id_menu) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label">Nama Menu</label>
        <input name="nama_menu" value="{{ old('nama_menu', $menu->nama_menu) }}" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Kategori</label>
        <input name="kategori_menu" value="{{ old('kategori_menu', $menu->kategori_menu) }}" class="form-control">
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Harga (angka)</label>
          <input name="harga" type="number" min="0" value="{{ old('harga', $menu->harga) }}" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Stok Harian</label>
          <input name="stok_harian" type="number" min="0" value="{{ old('stok_harian', $menu->stok_harian) }}" class="form-control">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Keterangan</label>
        <textarea name="detail_menu" class="form-control" rows="3">{{ old('detail_menu', $menu->detail_menu) }}</textarea>
      </div>

      <div class="d-flex gap-2">
        <a href="{{ route('menus.index') }}" class="btn btn-light">Batal</a>
        <button class="btn btn-primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>
@endsection
