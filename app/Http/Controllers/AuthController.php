<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login_id' => 'required|string',
        ], [
            'login_id.required' => 'লগইন আইডি দিন',
        ]);

        $user = User::where('login_id', $request->login_id)->first();

        if (!$user) {
            return back()->withErrors(['login_id' => 'লগইন আইডি সঠিক নয়']);
        }

        if (!$user->is_active) {
            return back()->withErrors(['login_id' => 'আপনার অ্যাকাউন্ট নিষ্ক্রিয়']);
        }

        Auth::login($user);

        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        }

        return redirect('/employee/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}
