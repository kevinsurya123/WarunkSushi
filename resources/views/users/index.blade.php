@extends('layouts.app')

@section('page_title','Data Pegawai')

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5>Data Pegawai</h5>
    <a href="{{ route('users.create') }}" class="btn btn-primary">+ Tambah Pegawai</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <form method="GET" class="mb-3">
    <div class="input-group" style="max-width:480px;">
      <input name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Cari nama atau username...">
      <button class="btn btn-outline-secondary">Cari</button>
    </div>
  </form>

  <div class="card">
    <div class="card-body p-0">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Username</th>
            <th>Role</th>
            <th>Dibuat</th>
            <th style="width:160px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $u)
          <tr>
            <td>{{ $u->id_user }}</td>
            <td>{{ $u->nama_user }}</td>
            <td>{{ $u->username }}</td>
            <td>{{ $u->role }}</td>
            <td>{{ $u->created_at?->format('Y-m-d') }}</td>
            <td>
              <a href="{{ route('users.edit', $u->id_user) }}" class="btn btn-sm btn-outline-primary">Edit</a>

              <form action="{{ route('users.destroy', $u->id_user) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus pegawai ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Hapus</button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" class="text-center py-4">Belum ada data</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    {{ $users->links() }}
  </div>
</div>
@endsection
