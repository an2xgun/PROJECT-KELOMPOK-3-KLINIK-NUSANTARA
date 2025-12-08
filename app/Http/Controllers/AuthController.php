<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function index()
    {
        return view('auth.login', ['title' => 'Login']);
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            
            // Redirect berdasarkan role
            $user = Auth::user();
            
            switch($user->role) {
                case 'admin':
                    return redirect()->route('dashboard')->with('success', 'Login berhasil sebagai Admin!');
                case 'dokter':
                    return redirect()->route('dashboard')->with('success', 'Login berhasil sebagai Dokter!');
                case 'petugas_pendaftaran':
                    return redirect()->route('dashboard')->with('success', 'Login berhasil sebagai Petugas Pendaftaran!');
                // removed dedicated 'perawat' role; fallthrough to generic dashboard message
                case 'kasir':
                    return redirect()->route('dashboard')->with('success', 'Login berhasil sebagai Kasir!');
                case 'apoteker':
                    return redirect()->route('dashboard')->with('success', 'Login berhasil sebagai Apoteker!');
                default:
                    return redirect()->route('dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }
}
