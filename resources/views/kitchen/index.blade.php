@extends('layouts.app')

@section('page_title','Kitchen Order')

@section('content')
<div class="container-fluid">
  <h4 class="mb-3">📋 Pesanan Masuk (Kitchen)</h4>

  @forelse($orders as $order)
    <div class="card mb-3">
      <div class="card-body">
        <h6>
          #Pesanan {{ $order->id_transaksi }}
          <span class="badge bg-secondary">{{ strtoupper($order->status) }}</span>
        </h6>

        <ul class="list-group mb-3">
          @foreach($order->items as $item)
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                {{ $item->menu->nama_menu }} x {{ $item->qty }}
              </div>

              <form method="POST" action="{{ route('kitchen.item.toggle',$item->id) }}">
                @csrf
                <button class="btn btn-sm {{ $item->is_done ? 'btn-success' : 'btn-outline-secondary' }}">
                  {{ $item->is_done ? '✅' : '⏳' }}
                </button>
              </form>
            </li>
          @endforeach
        </ul>

        <form method="POST" action="{{ route('kitchen.order.done',$order->id_transaksi) }}">
          @csrf
          <button class="btn btn-primary w-100"
            {{ $order->status == 'done' ? 'disabled' : '' }}>
            DONE
          </button>
        </form>
      </div>
    </div>
  @empty
    <div class="alert alert-success">
      🎉 Tidak ada pesanan, dapur santai
    </div>
  @endforelse
</div>
@endsection
