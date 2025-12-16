@extends('layouts.app')

@section('page_title','Data Customer')

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5>Data Customer</h5>
    <a href="{{ route('customers.create') }}" class="btn btn-sm btn-primary">+ Tambah Customer</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success py-2">
      {{ session('success') }}
    </div>
  @endif

  <form method="GET" class="mb-3">
    <div class="row g-2">
      <div class="col-md-4">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control"
               placeholder="Cari nama / no HP / email">
      </div>
      <div class="col-md-2">
        <button class="btn btn-outline-secondary w-100">Cari</button>
      </div>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-sm align-middle">
      <thead>
        <tr>
          <th style="width:40px;">#</th>
          <th>Nama</th>
          <th>No HP</th>
          <th>Email</th>
          <th style="width:150px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
      @forelse($customers as $index => $c)
        <tr>
          <td>{{ $customers->firstItem() + $index }}</td>
          <td>{{ $c->nama_customer }}</td>
          <td>{{ $c->no_hp ?: '-' }}</td>
          <td>{{ $c->email ?: '-' }}</td>
          <td>
            <a href="{{ route('customers.edit', $c->id_customer) }}" class="btn btn-sm btn-outline-primary">
              Edit
            </a>
            <form action="{{ route('customers.destroy', $c->id_customer) }}" method="POST"
                  class="d-inline"
                  onsubmit="return confirm('Hapus customer ini?');">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Hapus</button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="text-center text-muted">Belum ada data customer.</td>
        </tr>
      @endforelse
      </tbody>
    </table>
  </div>

  <div>
    {{ $customers->links() }}
  </div>
</div>
@endsection
