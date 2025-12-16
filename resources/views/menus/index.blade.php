@extends('layouts.app')

@section('page_title','Daftar Menu')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-0">Daftar Menu</h3>
            <small class="text-muted">Daftar menu Warunk Sushi</small>
        </div>

        <a href="{{ route('menus.create') }}" class="btn btn-primary">
            + Tambah Menu
        </a>
    </div>

    {{-- form cari --}}
    <form method="GET" action="{{ route('menus.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="q" value="{{ request('q') }}"
                   class="form-control" placeholder="Cari nama atau kategori...">
            <button class="btn btn-outline-secondary" type="submit">Cari</button>
        </div>
    </form>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:60px;">#</th>
                            <th style="width:70px;">Foto</th>
                            <th>Nama Menu</th>
                            <th>Kategori</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Stok</th>
                            <th style="width:150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menus as $index => $menu)
                            <tr>
                                <td>{{ $menus->firstItem() + $index }}</td>

                                {{-- FOTO MENU --}}
                                <td>
                                    @if($menu->gambar)
                                        <img src="{{ asset('storage/menus/'.$menu->gambar) }}"
                                             alt="{{ $menu->nama_menu }}"
                                             class="rounded"
                                             style="width:48px;height:48px;object-fit:cover;">
                                    @else
                                        <div class="bg-light border rounded d-flex align-items-center justify-content-center"
                                             style="width:48px;height:48px;font-size:10px;color:#999;">
                                            No Image
                                        </div>
                                    @endif
                                </td>

                                <td>{{ $menu->nama_menu }}</td>
                                <td>{{ $menu->kategori_menu }}</td>
                                <td class="text-end">Rp {{ number_format($menu->harga,0,',','.') }}</td>
                                <td class="text-end">{{ $menu->stok_harian }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('menus.edit', $menu->id_menu) }}"
                                           class="btn btn-sm btn-outline-primary">Edit</a>

                                        <form action="{{ route('menus.destroy', $menu->id_menu) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    Belum ada data menu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if(method_exists($menus, 'links'))
            <div class="card-footer">
                {{ $menus->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
