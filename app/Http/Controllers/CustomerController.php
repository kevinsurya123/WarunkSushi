<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Kalau mau wajib login:
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index(Request $request)
    {
        $query = Customer::query();

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_customer', 'like', '%' . $search . '%')
                  ->orWhere('no_hp', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $customers = $query
            ->orderBy('nama_customer')
            ->paginate(10)
            ->withQueryString();

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_customer' => 'required|string|max:255',
            'no_hp'         => 'nullable|string|max:50',
            'email'         => 'nullable|email|max:150',
        ]);

        Customer::create($request->only('nama_customer', 'no_hp', 'email'));

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);

        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_customer' => 'required|string|max:255',
            'no_hp'         => 'nullable|string|max:50',
            'email'         => 'nullable|email|max:150',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($request->only('nama_customer', 'no_hp', 'email'));

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete(); // soft delete

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer berhasil dihapus.');
    }
}
