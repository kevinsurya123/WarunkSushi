@extends('layouts.app')

@section('page_title', 'Laporan Penjualan')

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5>Laporan Penjualan</h5>

    <form method="GET" action="{{ route('reports.index') }}" class="d-flex align-items-end gap-2">
      <div>
        <label class="form-label mb-1">Dari Tanggal</label>
        <input type="date" name="from" class="form-control form-control-sm"
               value="{{ request('from', $from->toDateString()) }}">
      </div>
      <div>
        <label class="form-label mb-1">Sampai Tanggal</label>
        <input type="date" name="to" class="form-control form-control-sm"
               value="{{ request('to', $to->toDateString()) }}">
      </div>
      <div class="d-flex gap-2">
        <button class="btn btn-sm btn-outline-secondary">Filter</button>
        <a href="{{ route('reports.pdf', ['from' => $from->toDateString(), 'to' => $to->toDateString()]) }}"
           class="btn btn-sm btn-danger" target="_blank">
          PDF
        </a>
      </div>
    </form>
  </div>

  {{-- Kartu ringkasan kecil --}}
  <div class="row mb-3">
    <div class="col-md-4 mb-2">
      <div class="card p-3">
        <div class="small text-muted">Total Hari</div>
        <div class="h5 mb-0">{{ $daily->count() }} hari</div>
      </div>
    </div>
    <div class="col-md-4 mb-2">
      @php
        $totalTransaksi = $daily->sum('jumlah_transaksi');
      @endphp
      <div class="card p-3">
        <div class="small text-muted">Jumlah Transaksi</div>
        <div class="h5 mb-0">{{ $totalTransaksi }}</div>
      </div>
    </div>
    <div class="col-md-4 mb-2">
      @php
        $totalOmzet = $daily->sum('total_omzet');
      @endphp
      <div class="card p-3">
        <div class="small text-muted">Total Omzet</div>
        <div class="h5 mb-0">Rp {{ number_format($totalOmzet,0,',','.') }}</div>
      </div>
    </div>
  </div>

  <div class="row">
    {{-- Grafik kiri --}}
    <div class="col-lg-8 mb-3">
      <div class="card p-3">
        <h6 class="mb-3">Penjualan per Hari</h6>
        <canvas id="chart-penjualan"></canvas>
      </div>
    </div>

    {{-- Grafik kanan --}}
    <div class="col-lg-4 mb-3">
      <div class="card p-3 mb-3">
        <h6 class="mb-3">Top 5 Menu Terlaris (Qty)</h6>
        <canvas id="chart-menu"></canvas>
      </div>

      <div class="card p-3">
        <h6 class="mb-3">Metode Pembayaran</h6>
        <canvas id="chart-payment"></canvas>
      </div>
    </div>
  </div>

  {{-- Tabel-tabel laporan --}}
  <div class="row mt-4">
    <div class="col-lg-6 mb-3">
      <div class="card p-3">
        <h6 class="mb-3">Penjualan Harian</h6>
        <div class="table-responsive">
          <table class="table table-sm">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th class="text-end">Jumlah Transaksi</th>
                <th class="text-end">Total Omzet</th>
              </tr>
            </thead>
            <tbody>
              @forelse($daily as $row)
                <tr>
                  <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}</td>
                  <td class="text-end">{{ $row->jumlah_transaksi }}</td>
                  <td class="text-end">Rp {{ number_format($row->total_omzet,0,',','.') }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-center text-muted">Tidak ada data.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-lg-6 mb-3">
      <div class="card p-3">
        <h6 class="mb-3">Penjualan per Menu (Top)</h6>
        <div class="table-responsive">
          <table class="table table-sm">
            <thead>
              <tr>
                <th>Menu</th>
                <th class="text-end">Qty</th>
                <th class="text-end">Total Omzet</th>
              </tr>
            </thead>
            <tbody>
              @forelse($perMenu as $row)
                <tr>
                  <td>{{ $row->nama_menu }}</td>
                  <td class="text-end">{{ $row->total_qty }}</td>
                  <td class="text-end">Rp {{ number_format($row->total_omzet,0,',','.') }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-center text-muted">Tidak ada data.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <div class="card p-3 mt-3">
        <h6 class="mb-3">Ringkasan Metode Pembayaran</h6>
        <div class="table-responsive">
          <table class="table table-sm">
            <thead>
              <tr>
                <th>Metode</th>
                <th class="text-end">Jumlah Transaksi</th>
                <th class="text-end">Total Omzet</th>
              </tr>
            </thead>
            <tbody>
              @forelse($perPayment as $row)
                <tr>
                  <td>{{ strtoupper($row->payment_method) }}</td>
                  <td class="text-end">{{ $row->jumlah_transaksi }}</td>
                  <td class="text-end">Rp {{ number_format($row->total_omzet,0,',','.') }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-center text-muted">Tidak ada data.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
  {{-- Chart.js CDN --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const chartDays       = @json($chartDays);
    const chartTotals     = @json($chartTotals);
    const chartMenuNames  = @json($chartMenuNames);
    const chartMenuQty    = @json($chartMenuQty);
    const chartPayMethods = @json($chartPayMethods);
    const chartPayTotals  = @json($chartPayTotals);

    // Line chart penjualan harian
    const ctxPenjualan = document.getElementById('chart-penjualan').getContext('2d');
    new Chart(ctxPenjualan, {
      type: 'line',
      data: {
        labels: chartDays,
        datasets: [{
          label: 'Omzet',
          data: chartTotals,
          tension: 0.3,
          fill: false,
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });

    // Bar chart menu terlaris
    const ctxMenu = document.getElementById('chart-menu').getContext('2d');
    new Chart(ctxMenu, {
      type: 'bar',
      data: {
        labels: chartMenuNames,
        datasets: [{
          label: 'Qty',
          data: chartMenuQty,
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero: true }
        }
      }
    });

    // Pie chart metode pembayaran
    const ctxPay = document.getElementById('chart-payment').getContext('2d');
    new Chart(ctxPay, {
      type: 'pie',
      data: {
        labels: chartPayMethods,
        datasets: [{
          data: chartPayTotals,
        }]
      },
      options: {
        responsive: true,
      }
    });
  </script>
@endsection
