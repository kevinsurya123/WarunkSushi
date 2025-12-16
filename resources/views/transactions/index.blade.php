@extends('layouts.app')

@section('page_title','Transaksi')

@section('content')
<div class="container-fluid">
    {{-- Header + Tombol --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-0">Transaksi</h5>
            <small class="text-muted">Daftar transaksi kasir</small>
        </div>

        <div class="d-flex gap-2">
            {{-- tombol tambah transaksi --}}
            <a href="{{ route('transactions.create') }}" class="btn btn-sm btn-primary">
                + Tambah Transaksi
            </a>

            {{-- tombol menuju halaman report --}}
            <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-secondary">
                Lihat Laporan
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                    <tr>
                        <th style="width:60px;">#</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Kasir</th>
                        <th class="text-end">Total</th>
                        <th>Metode</th>
                        <th style="width:100px;">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($transactions as $index => $trx)
                        <tr>
                            <td>{{ $transactions->firstItem() + $index }}</td>
                            <td>{{ $trx->created_at }}</td>
                            <td>{{ $trx->customer->nama_customer ?? '-' }}</td>
                            <td>{{ $trx->user->name ?? '-' }}</td>
                            <td class="text-end">
                                Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                            </td>
                            <td>{{ strtoupper($trx->payment_method) }}</td>
                            <td>
                                <a href="{{ route('transactions.show', $trx->id_transaksi) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Belum ada transaksi.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- kalau pakai paginate --}}
        @if(method_exists($transactions, 'links'))
            <div class="card-footer">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
