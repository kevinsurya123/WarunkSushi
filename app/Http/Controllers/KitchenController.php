<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index()
    {
        $orders = Transaction::with(['items.menu'])
            ->whereIn('status', ['new','processing'])
            ->orderBy('created_at')
            ->get();

        return view('kitchen.index', compact('orders'));
    }

    public function toggleItem($id)
    {
        $item = TransactionItem::findOrFail($id);
        $item->is_done = !$item->is_done;
        $item->save();

        // update status transaksi
        $trx = $item->transaction;
        $totalItems = $trx->items->count();
        $doneItems  = $trx->items->where('is_done',1)->count();

        if ($doneItems > 0) {
            $trx->status = 'processing';
            if ($doneItems == $totalItems) {
                $trx->status = 'done';
            }
            $trx->save();
        }

        return back();
    }

    public function doneOrder($id)
    {
        $trx = Transaction::findOrFail($id);
        $trx->status = 'done';
        $trx->save();

        return back()->with('success','Pesanan selesai');
    }
}
