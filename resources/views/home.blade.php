@extends('layouts.app')

@section('page_title','Dashboard')

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Dashboard</h5>

    {{-- Filter bulan --}}
    <form method="GET" action="{{ route('home') }}" class="d-flex align-items-center gap-2">
      <span class="small text-muted">Bulan data:</span>
      <select name="month" class="form-select form-select-sm" onchange="this.form.submit()">
        @foreach($monthOptions as $opt)
          <option value="{{ $opt['value'] }}" {{ $opt['value'] == $selectedMonth ? 'selected' : '' }}>
            {{ $opt['label'] }}
          </option>
        @endforeach
      </select>
    </form>
  </div>

  {{-- Kartu ringkasan (tidak ikut filter bulan) --}}
  <div class="row mb-3">
    <div class="col-md-4 mb-2">
      <div class="card p-3">
        <div class="small text-muted">Jumlah Pegawai</div>
        <div class="h4 mb-0">{{ $totalPegawai }}</div>
      </div>
    </div>
    <div class="col-md-4 mb-2">
      <div class="card p-3">
        <div class="small text-muted">Jumlah Menu</div>
        <div class="h4 mb-0">{{ $totalMenu }}</div>
      </div>
    </div>
    <div class="col-md-4 mb-2">
      <div class="card p-3">
        <div class="small text-muted">Penjualan Hari Ini</div>
        <div class="h4 mb-0">Rp {{ number_format($todaySales,0,',','.') }}</div>
      </div>
    </div>
  </div>

  {{-- Grafik harian + pie untuk bulan terpilih --}}
  <div class="row">
    <div class="col-lg-8 mb-3">
      <div class="card p-3">
        <h6 class="mb-1">Omzet per Hari ({{ $selectedMonthLabel }})</h6>
        <small class="text-muted">Menampilkan omzet tiap hari pada bulan yang dipilih</small>
        <canvas id="chart-penjualan-dashboard" height="120" class="mt-2"></canvas>
      </div>
    </div>
    <div class="col-lg-4 mb-3">
      <div class="card p-3">
        <h6 class="mb-1">Omzet per Metode Pembayaran ({{ $selectedMonthLabel }})</h6>
        <small class="text-muted">Distribusi omzet per metode bayar pada bulan yang dipilih</small>
        <canvas id="chart-payment-dashboard" height="120" class="mt-2"></canvas>
      </div>
    </div>
  </div>

  {{-- History per bulan: 6 bulan terakhir sampai bulan terpilih --}}
  <div class="row">
    <div class="col-lg-8 mb-3">
      <div class="card p-3">
        <h6 class="mb-1">History Penjualan per Bulan ({{ count($historyMonthLabels) }} Bulan Terakhir)</h6>
        <small class="text-muted">Sampai dengan {{ $selectedMonthLabel }}</small>
        <canvas id="chart-history-monthly" height="120" class="mt-2"></canvas>
      </div>
    </div>
    <div class="col-lg-4 mb-3">
      <div class="card p-3">
        <h6 class="mb-3">Ringkasan Penjualan per Bulan</h6>
        <div class="table-responsive">
          <table class="table table-sm mb-0 align-middle">
            <thead>
              <tr>
                <th>Bulan</th>
                <th class="text-end">Total Penjualan</th>
              </tr>
            </thead>
            <tbody>
              @foreach($historyTable as $row)
                <tr>
                  <td>{{ $row['label'] }}</td>
                  <td class="text-end">
                    Rp {{ number_format($row['total'], 0, ',', '.') }}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
  {{-- load Chart.js --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const dashDays        = @json($chartDays);
      const dashTotals      = @json($chartTotals);
      const dashPayMethods  = @json($chartPayMethods);
      const dashPayTotals   = @json($chartPayTotals);
      const histMonthLabels = @json($historyMonthLabels);
      const histMonthTotals = @json($historyMonthTotals);

      // Line chart omzet per hari (bulan terpilih)
      const ctxDashSales = document
        .getElementById('chart-penjualan-dashboard')
        .getContext('2d');

      new Chart(ctxDashSales, {
        type: 'line',
        data: {
          labels: dashDays,
          datasets: [{
            label: 'Omzet',
            data: dashTotals,
            tension: 0.3,
            fill: false,
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
      const ctxDashPay = document
        .getElementById('chart-payment-dashboard')
        .getContext('2d');

      new Chart(ctxDashPay, {
        type: 'pie',
        data: {
          labels: dashPayMethods,
          datasets: [{
            data: dashPayTotals,
          }]
        },
        options: { responsive: true }
      });

      // Bar chart history per bulan
      const ctxHistMonth = document
        .getElementById('chart-history-monthly')
        .getContext('2d');

      new Chart(ctxHistMonth, {
        type: 'bar',
        data: {
          labels: histMonthLabels,
          datasets: [{
            label: 'Omzet per Bulan',
            data: histMonthTotals,
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
    });
  </script>
@endsection
