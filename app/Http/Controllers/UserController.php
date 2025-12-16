<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $users = User::when($q, fn($b) => $b->where('nama_user','like',"%{$q}%")
                                         ->orWhere('username','like',"%{$q}%"))
                     ->orderBy('id_user','desc')
                     ->paginate(12)
                     ->appends(['q'=>$q]);

        return view('users.index', compact('users','q'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'nama_user' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
            'role' => ['required', Rule::in(['owner','manager','pegawai'])]
        ]);

        $dataToSave = [
            'nama_user' => $data['nama_user'],
            'username'  => $data['username'],
            'password_hash' => Hash::make($data['password']),
            'role' => $data['role']
        ];

        User::create($dataToSave);

        return redirect()->route('users.index')->with('success','Pegawai berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $r, User $user)
    {
        $data = $r->validate([
            'nama_user' => 'required|string|max:255',
            'username' => ['required','string','max:100', Rule::unique('users','username')->ignore($user->id_user,'id_user')],
            'password' => 'nullable|string|min:6|confirmed',
            'role' => ['required', Rule::in(['owner','manager','pegawai'])]
        ]);

        $user->nama_user = $data['nama_user'];
        $user->username = $data['username'];
        $user->role = $data['role'];

        if (!empty($data['password'])) {
            $user->password_hash = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('users.index')->with('success','Pegawai berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Karena model memakai SoftDeletes, delete() akan soft-delete
        $user->delete();

        return redirect()->route('users.index')->with('success','Pegawai berhasil dihapus.');
    }
}
