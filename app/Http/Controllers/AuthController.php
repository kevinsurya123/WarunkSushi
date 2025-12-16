<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // tampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // proses login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // cari user berdasarkan username
        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password_hash)) {
            // login pakai id_user
            Auth::loginUsingId($user->id_user);

            // simpan role jika mau dipakai
            session(['role' => $user->role]);

            // redirect ke dashboard /home (atau intended)
            return redirect()->intended('/home');
        }

        return back()->withErrors(['username' => 'Username atau password salah'])->withInput();
    }

    // logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
