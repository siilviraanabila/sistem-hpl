<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Shm;
use App\Models\PermasalahanLahan;
use App\Models\Hpl;
use App\Models\KawasanTransmigrasi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SesiController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }

    public function getLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'pimpinan' => redirect()->route('pimpinan.dashboard'),
            };
        }

        return back()->withErrors(['login' => 'Email atau password salah']);
    }


    function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function getRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'email' => 'required|email|max:255|unique:users,email',
            'name' => 'required|min:3|max:255',
            'password' => 'required|string|min:6|max:10',
            'role' => 'required|in:admin,pimpinan'
        ]);

        User::create([
            'email' => $validatedData['email'],
            'name' => $validatedData['name'],
            'role' => $validatedData['role'],
            'password' => Hash::make($validatedData['password']),
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }
    
}
