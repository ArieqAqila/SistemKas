<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function loginPage()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|exists:users,username|min:6|max:15',
            'password' => 'required|min:6|max:15',
        ]);
 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            return $user->hak_akses === 'warga' 
                                            ? redirect()->intended(route('dashboard-warga')) 
                                            : redirect()->intended(route('dashboard-admin'));
        }

        return back()->withErrors(['message' => 'Username atau Password salah!']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect()->route('login')->with('success', 'Berhasil Logout!');
    }
}
