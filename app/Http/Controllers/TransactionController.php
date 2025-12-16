<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Menu;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('customer', 'user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $menus = Menu::orderBy('nama_menu')->get();
        $customers = Customer::orderBy('nama_customer')->get();

        // siapkan data menu dalam bentuk array sederhana untuk dipakai di JS
        $menusJson = $menus->map(function ($m) {
            return [
                'id_menu'   => $m->id_menu,
                'nama_menu' => $m->nama_menu,
                'harga'     => $m->harga,
            ];
        })->values();

        return view('transactions.create', [
            'menus'     => $menus,
            'customers' => $customers,
            'menusJson' => $menusJson,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_customer'         => 'nullable|exists:customers,id_customer',
            'items'               => 'required|array|min:1',
            'items.*.id_menu'     => 'required|exists:menus,id_menu',
            'items.*.qty'         => 'required|integer|min:1',
            'items.*.price'       => 'required|numeric|min:0',
            'payment_amount'      => 'required|numeric|min:0',
            'payment_method'      => 'required|string|max:50',
        ]);

        DB::beginTransaction();

        try {
            // Hitung total
            $total = 0;
            foreach ($request->items as $item) {
                $total += $item['qty'] * $item['price'];
            }

            if ($request->payment_amount < $total) {
                return back()
                    ->withErrors(['payment_amount' => 'Uang bayar kurang dari total.'])
                    ->withInput();
            }

            // Simpan ke tabel transactions
            $transaction = Transaction::create([
                'id_customer'    => $request->id_customer,
                'id_user'        => Auth::id(),
                'total_amount'   => $total,
                'payment_amount' => $request->payment_amount,
                'change_amount'  => $request->payment_amount - $total,
                'payment_method' => $request->payment_method,
            ]);

            // Simpan detail item
        foreach ($request->items as $item) {
    TransactionItem::create([
        'id_transaksi' => $transaction->id_transaksi,
        'id_menu'      => $item['id_menu'],
        'qty'          => $item['qty'],
        'price'        => $item['price'],
        'harga_satuan' => $item['price'],                 // isi juga kolom lama
        
    ]);
}



            DB::commit();

            return redirect()
                ->route('transactions.show', $transaction->id_transaksi)
                ->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors([$e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        $transaction = Transaction::with('items.menu', 'customer', 'user')
            ->findOrFail($id);

        return view('transactions.show', compact('transaction'));
    }
}
