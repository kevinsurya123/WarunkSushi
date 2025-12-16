<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Menu;
use App\Models\Transaction;
use Carbon\Carbon;

class HomeController extends Controller
{

public function index(Request $request)
{
    // ==== CARD RINGKASAN (tetap, tidak ikut filter bulan) ====
    $totalPegawai = User::count();
    $totalMenu    = Menu::count();
    $today        = Carbon::today();
    $todaySales   = Transaction::whereDate('created_at', $today)->sum('total_amount');

    // ==== FILTER BULAN ====
    $monthParam = $request->query('month'); // format: YYYY-MM
    if ($monthParam && preg_match('/^\d{4}-\d{2}$/', $monthParam)) {
        $selectedMonth = Carbon::createFromFormat('Y-m', $monthParam)->startOfMonth();
    } else {
        $selectedMonth = Carbon::now()->startOfMonth();
    }
    $selectedMonthLabel = $selectedMonth->format('M Y');

    // opsi select: 12 bulan terakhir (termasuk bulan sekarang)
    $monthOptions = [];
    for ($i = 0; $i < 12; $i++) {
        $m = Carbon::now()->subMonths($i)->startOfMonth();
        $monthOptions[] = [
            'value' => $m->format('Y-m'),
            'label' => $m->format('M Y'),
        ];
    }

    // RENTANG HARI UNTUK BULAN YANG DIPILIH
    $fromDay = $selectedMonth->copy()->startOfMonth();
    $toDay   = $selectedMonth->copy()->endOfMonth();

    // ============ GRAFIK HARIAN UNTUK BULAN TERPILIH ============
    $daily = Transaction::selectRaw('DATE(created_at) as tanggal')
        ->selectRaw('SUM(total_amount) as total_omzet')
        ->whereBetween('created_at', [$fromDay, $toDay])
        ->groupBy('tanggal')
        ->orderBy('tanggal')
        ->get();

    $chartDays   = [];
    $chartTotals = [];

    $period = new \DatePeriod($fromDay, new \DateInterval('P1D'), $toDay->copy()->addDay());
    foreach ($period as $date) {
        $label = $date->format('d M');
        $chartDays[] = $label;

        $row = $daily->firstWhere('tanggal', $date->format('Y-m-d'));
        $chartTotals[] = $row ? (float) $row->total_omzet : 0;
    }

    // ============ PIE CHART METODE PEMBAYARAN UNTUK BULAN TERPILIH ============
    $perPayment = Transaction::selectRaw('payment_method')
        ->selectRaw('SUM(total_amount) as total_omzet')
        ->whereBetween('created_at', [$fromDay, $toDay])
        ->groupBy('payment_method')
        ->orderBy('payment_method')
        ->get();

    $chartPayMethods = [];
    $chartPayTotals  = [];
    foreach ($perPayment as $row) {
        $chartPayMethods[] = strtoupper($row->payment_method);
        $chartPayTotals[]  = (float) $row->total_omzet;
    }

    // ============ HISTORY PENJUALAN PER BULAN (6 BULAN TERAKHIR SAMPAI BULAN TERPILIH) ============
    $monthsBack  = 6;
    $fromMonth   = $selectedMonth->copy()->subMonths($monthsBack - 1)->startOfMonth();
    $toMonth     = $selectedMonth->copy()->endOfMonth();

    $monthly = Transaction::selectRaw("DATE_FORMAT(created_at, '%Y-%m-01') as bulan")
        ->selectRaw('SUM(total_amount) as total_omzet')
        ->whereBetween('created_at', [$fromMonth, $toMonth])
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get();

    $historyMonthLabels = [];
    $historyMonthTotals = [];
    $historyTable       = [];

    $monthPeriod = new \DatePeriod(
        $fromMonth,
        new \DateInterval('P1M'),
        $selectedMonth->copy()->startOfMonth()->addMonth()
    );

    foreach ($monthPeriod as $date) {
        $key   = $date->format('Y-m-01');
        $label = $date->format('M Y');

        $row   = $monthly->firstWhere('bulan', $key);
        $total = $row ? (float) $row->total_omzet : 0;

        $historyMonthLabels[] = $label;
        $historyMonthTotals[] = $total;
        $historyTable[] = [
            'label' => $label,
            'total' => $total,
        ];
    }

    return view('home', [
        'totalPegawai'        => $totalPegawai,
        'totalMenu'           => $totalMenu,
        'todaySales'          => $todaySales,
        'selectedMonth'       => $selectedMonth->format('Y-m'),
        'selectedMonthLabel'  => $selectedMonthLabel,
        'monthOptions'        => $monthOptions,
        'chartDays'           => $chartDays,
        'chartTotals'         => $chartTotals,
        'chartPayMethods'     => $chartPayMethods,
        'chartPayTotals'      => $chartPayTotals,
        'historyMonthLabels'  => $historyMonthLabels,
        'historyMonthTotals'  => $historyMonthTotals,
        'historyTable'        => $historyTable,
    ]);
}


}
