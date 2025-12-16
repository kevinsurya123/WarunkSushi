@extends('layouts.app')

@section('page_title','Detail Transaksi')

@section('content')
<div class="container-fluid" style="max-width:900px;">
  <div class="card p-4">
    <h5>Detail Transaksi #{{ $transaction->id_transaksi }}</h5>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
      <strong>Tanggal:</strong> {{ $transaction->created_at }} <br>
      <strong>Pelanggan:</strong> {{ $transaction->customer->nama_customer ?? 'Umum' }} <br>
      <strong>Kasir:</strong> {{ $transaction->user->name ?? '-' }} <br>
      <strong>Metode:</strong> {{ strtoupper($transaction->payment_method) }}
    </div>

    <table class="table table-sm">
      <thead>
        <tr>
          <th>Menu</th>
          <th>Harga</th>
          <th>Qty</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @foreach($transaction->items as $item)
        <tr>
          <td>{{ $item->menu->nama_menu ?? '-' }}</td>
          <td>Rp {{ number_format($item->price,0,',','.') }}</td>
          <td>{{ $item->qty }}</td>
          <td>Rp {{ number_format($item->subtotal,0,',','.') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <div class="text-end mt-3">
      <p>Total: <strong>Rp {{ number_format($transaction->total_amount,0,',','.') }}</strong></p>
      <p>Bayar: <strong>Rp {{ number_format($transaction->payment_amount,0,',','.') }}</strong></p>
      <p>Kembali: <strong>Rp {{ number_format($transaction->change_amount,0,',','.') }}</strong></p>
    </div>

    <div class="mt-3">
      <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
  </div>
</div>
@endsection
