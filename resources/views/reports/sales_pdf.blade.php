<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Laporan Penjualan</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
    th, td { border: 1px solid #000; padding: 4px 6px; }
    th { background: #eee; }
    h3, h4 { margin: 4px 0; }
  </style>
</head>
<body>
  <h3>Laporan Penjualan Warunk Sushi</h3>
  <h4>Periode: {{ $from->format('d M Y') }} s/d {{ $to->format('d M Y') }}</h4>
  <hr>

  <h4>1. Penjualan Harian</h4>
  <table>
    <thead>
      <tr>
        <th>Tanggal</th>
        <th>Jumlah Transaksi</th>
        <th>Total Omzet</th>
      </tr>
    </thead>
    <tbody>
      @forelse($daily as $row)
        <tr>
          <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}</td>
          <td style="text-align:right;">{{ $row->jumlah_transaksi }}</td>
          <td style="text-align:right;">Rp {{ number_format($row->total_omzet,0,',','.') }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="3" style="text-align:center;">Tidak ada data.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <h4>2. Penjualan per Menu (Top)</h4>
  <table>
    <thead>
      <tr>
        <th>Menu</th>
        <th>Qty</th>
        <th>Total Omzet</th>
      </tr>
    </thead>
    <tbody>
      @forelse($perMenu as $row)
        <tr>
          <td>{{ $row->nama_menu }}</td>
          <td style="text-align:right;">{{ $row->total_qty }}</td>
          <td style="text-align:right;">Rp {{ number_format($row->total_omzet,0,',','.') }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="3" style="text-align:center;">Tidak ada data.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <h4>3. Ringkasan Metode Pembayaran</h4>
  <table>
    <thead>
      <tr>
        <th>Metode</th>
        <th>Jumlah Transaksi</th>
        <th>Total Omzet</th>
      </tr>
    </thead>
    <tbody>
      @forelse($perPayment as $row)
        <tr>
          <td>{{ strtoupper($row->payment_method) }}</td>
          <td style="text-align:right;">{{ $row->jumlah_transaksi }}</td>
          <td style="text-align:right;">Rp {{ number_format($row->total_omzet,0,',','.') }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="3" style="text-align:center;">Tidak ada data.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

</body>
</html>
