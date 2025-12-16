<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // range tanggal (default 7 hari terakhir)
        $to   = $request->input('to')   ? Carbon::parse($request->input('to'))->endOfDay()   : Carbon::now()->endOfDay();
        $from = $request->input('from') ? Carbon::parse($request->input('from'))->startOfDay() : Carbon::now()->subDays(6)->startOfDay();

        // 1) Penjualan per hari
        $daily = Transaction::selectRaw('DATE(created_at) as tanggal')
            ->selectRaw('COUNT(*) as jumlah_transaksi')
            ->selectRaw('SUM(total_amount) as total_omzet')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // 2) Penjualan per menu (top 5)
        $perMenu = TransactionItem::query()
            ->join('transactions', 'transaction_items.id_transaksi', '=', 'transactions.id_transaksi')
            ->join('menus', 'transaction_items.id_menu', '=', 'menus.id_menu')
            ->whereBetween('transactions.created_at', [$from, $to])
            ->selectRaw('menus.nama_menu as nama_menu')
            ->selectRaw('SUM(transaction_items.qty) as total_qty')
            ->selectRaw('SUM(transaction_items.qty * transaction_items.price) as total_omzet')
            ->groupBy('menus.nama_menu')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // 3) Ringkasan metode pembayaran
        $perPayment = Transaction::selectRaw('payment_method')
            ->selectRaw('COUNT(*) as jumlah_transaksi')
            ->selectRaw('SUM(total_amount) as total_omzet')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('payment_method')
            ->orderBy('payment_method')
            ->get();

        // data untuk grafik
        $chartDays   = [];
        $chartTotals = [];
        foreach ($daily as $row) {
            $chartDays[]   = Carbon::parse($row->tanggal)->format('d M');
            $chartTotals[] = (float) $row->total_omzet;
        }

        $chartMenuNames = [];
        $chartMenuQty   = [];
        foreach ($perMenu as $row) {
            $chartMenuNames[] = $row->nama_menu;
            $chartMenuQty[]   = (int) $row->total_qty;
        }

        $chartPayMethods = [];
        $chartPayTotals  = [];
        foreach ($perPayment as $row) {
            $chartPayMethods[] = strtoupper($row->payment_method);
            $chartPayTotals[]  = (float) $row->total_omzet;
        }

        return view('reports.index', [
            'from'            => $from,
            'to'              => $to,
            'daily'           => $daily,
            'perMenu'         => $perMenu,
            'perPayment'      => $perPayment,
            'chartDays'       => $chartDays,
            'chartTotals'     => $chartTotals,
            'chartMenuNames'  => $chartMenuNames,
            'chartMenuQty'    => $chartMenuQty,
            'chartPayMethods' => $chartPayMethods,
            'chartPayTotals'  => $chartPayTotals,
        ]);
    }

    public function pdf(Request $request)
    {
        // pakai logika yang sama dengan index
        $to   = $request->input('to')   ? Carbon::parse($request->input('to'))->endOfDay()   : Carbon::now()->endOfDay();
        $from = $request->input('from') ? Carbon::parse($request->input('from'))->startOfDay() : Carbon::now()->subDays(6)->startOfDay();

        $daily = Transaction::selectRaw('DATE(created_at) as tanggal')
            ->selectRaw('COUNT(*) as jumlah_transaksi')
            ->selectRaw('SUM(total_amount) as total_omzet')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $perMenu = TransactionItem::query()
            ->join('transactions', 'transaction_items.id_transaksi', '=', 'transactions.id_transaksi')
            ->join('menus', 'transaction_items.id_menu', '=', 'menus.id_menu')
            ->whereBetween('transactions.created_at', [$from, $to])
            ->selectRaw('menus.nama_menu as nama_menu')
            ->selectRaw('SUM(transaction_items.qty) as total_qty')
            ->selectRaw('SUM(transaction_items.qty * transaction_items.price) as total_omzet')
            ->groupBy('menus.nama_menu')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        $perPayment = Transaction::selectRaw('payment_method')
            ->selectRaw('COUNT(*) as jumlah_transaksi')
            ->selectRaw('SUM(total_amount) as total_omzet')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('payment_method')
            ->orderBy('payment_method')
            ->get();

        $pdf = Pdf::loadView('reports.sales_pdf', [
            'from'       => $from,
            'to'         => $to,
            'daily'      => $daily,
            'perMenu'    => $perMenu,
            'perPayment' => $perPayment,
        ])->setPaper('a4', 'portrait');

        $filename = 'laporan-penjualan-' . $from->format('Ymd') . '-' . $to->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}
