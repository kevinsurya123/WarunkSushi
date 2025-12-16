@extends('layouts.app')

@section('page_title','Transaksi Baru')

@section('content')
<div class="container-fluid" style="max-width:1000px;">
  <div class="card p-4">
    <h5>Transaksi Baru</h5>

    @if($errors->any())
      <div class="alert alert-danger">
        {{ $errors->first() }}
      </div>
    @endif

    <form id="trx-form" action="{{ route('transactions.store') }}" method="POST">
      @csrf

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Pelanggan (opsional)</label>
          <select name="id_customer" class="form-select">
            <option value="">Umum / Walk-in</option>
            @foreach($customers as $c)
              <option value="{{ $c->id_customer }}">{{ $c->nama_customer }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Tanggal</label>
          <input type="text" class="form-control" value="{{ now() }}" disabled>
        </div>
      </div>

      <hr>

      <h6>Item Pesanan</h6>
      <div class="table-responsive mb-2">
        <table class="table align-middle" id="items-table">
          <thead>
            <tr>
              <th>Menu</th>
              <th style="width:120px;">Harga</th>
              <th style="width:100px;">Qty</th>
              <th style="width:120px;">Subtotal</th>
              <th style="width:60px;"></th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      <button type="button" class="btn btn-sm btn-outline-primary mb-3" id="add-item">+ Tambah Item</button>

      <div class="row justify-content-end">
        <div class="col-md-4">
          <div class="mb-2 d-flex justify-content-between">
            <span>Total</span>
            <strong id="total-display">Rp 0</strong>
          </div>
          <input type="hidden" id="total-input" value="0">

          <div class="mb-2">
            <label class="form-label">Uang Bayar</label>
            <input type="number" min="0" step="100" name="payment_amount" id="pay-input"
                   class="form-control" required>
          </div>
          <div class="mb-3 d-flex justify-content-between">
            <span>Kembalian</span>
            <strong id="change-display">Rp 0</strong>
          </div>

          <div class="mb-3">
            <label class="form-label">Metode Pembayaran</label>
            <select name="payment_method" class="form-select" required>
              <option value="cash">Cash</option>
              <option value="qris">QRIS</option>
              <option value="transfer">Transfer</option>
            </select>
          </div>

          <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('transactions.index') }}" class="btn btn-light">Batal</a>
            <button class="btn btn-primary">Simpan Transaksi</button>
          </div>
        </div>
      </div>

    </form>
  </div>
</div>

{{-- Siapkan array menu di PHP dulu, tanpa function/[] di dalam @json --}}
@php
  $menusJson = [];
  foreach ($menus as $m) {
      $menusJson[] = [
          'id_menu'   => $m->id_menu,
          'nama_menu' => $m->nama_menu,
          'harga'     => $m->harga,
      ];
  }
@endphp

<script>
  const MENUS = @json($menusJson);
</script>

@verbatim
<script>
  (function () {
    const tbody        = document.querySelector('#items-table tbody');
    const addBtn       = document.getElementById('add-item');
    const totalDisplay = document.getElementById('total-display');
    const totalInput   = document.getElementById('total-input');
    const payInput     = document.getElementById('pay-input');
    const changeDisplay= document.getElementById('change-display');

    let rowIndex = 0;

    function formatRupiah(n) {
      n = Number(n) || 0;
      return 'Rp ' + n.toLocaleString('id-ID');
    }

    function recalc() {
      let total = 0;
      tbody.querySelectorAll('tr').forEach(tr => {
        const qty   = Number(tr.querySelector('.item-qty').value || 0);
        const price = Number(tr.querySelector('.item-price').value || 0);
        const sub   = qty * price;
        tr.querySelector('.item-subtotal').value      = sub;
        tr.querySelector('.item-subtotal-text').innerText = formatRupiah(sub);
        total += sub;
      });
      totalDisplay.innerText = formatRupiah(total);
      totalInput.value = total;

      const pay = Number(payInput.value || 0);
      const change = pay - total;
      changeDisplay.innerText = formatRupiah(change >= 0 ? change : 0);
    }

    function addRow() {
      const tr = document.createElement('tr');

      let options = '<option value="">Pilih menu</option>';
      MENUS.forEach(m => {
        options += '<option value="'+m.id_menu+'" data-price="'+m.harga+'">'+m.nama_menu+'</option>';
      });

      tr.innerHTML =
        '<td>' +
          '<select class="form-select item-menu" name="items['+rowIndex+'][id_menu]" required>' +
            options +
          '</select>' +
        '</td>' +
        '<td>' +
          '<input type="number" class="form-control item-price" name="items['+rowIndex+'][price]" min="0" value="0" required>' +
        '</td>' +
        '<td>' +
          '<input type="number" class="form-control item-qty" name="items['+rowIndex+'][qty]" min="1" value="1" required>' +
        '</td>' +
        '<td>' +
          '<input type="hidden" class="item-subtotal" name="items['+rowIndex+'][subtotal]" value="0">' +
          '<span class="item-subtotal-text">Rp 0</span>' +
        '</td>' +
        '<td>' +
          '<button type="button" class="btn btn-sm btn-danger btn-remove">X</button>' +
        '</td>';

      tbody.appendChild(tr);
      rowIndex++;

      tr.querySelector('.item-menu').addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const price = selected.getAttribute('data-price') || 0;
        tr.querySelector('.item-price').value = price;
        recalc();
      });
      tr.querySelector('.item-price').addEventListener('input', recalc);
      tr.querySelector('.item-qty').addEventListener('input', recalc);
      tr.querySelector('.btn-remove').addEventListener('click', function () {
        tr.remove();
        recalc();
      });

      recalc();
    }

    addBtn.addEventListener('click', addRow);
    addRow(); // 1 baris awal

    payInput.addEventListener('input', recalc);
  })();
</script>
@endverbatim
@endsection