<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Show register page
    public function showRegister()
    {
        return view('auth.register');
    }

    // Register user
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'citizen'
        ]);

        return redirect('/login')->with('success', 'Account created successfully');
    }

    // Show login page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Login user (SESSION + COOKIE)
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::firstWhere('email', $request->email);

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Invalid credentials');
        }

        // Store session
        session([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role' => $user->role
        ]);

        // Remember me cookie (optional)
        if ($request->has('remember')) {
            cookie()->queue('user_email', $user->email, 60 * 24 * 7);
        }

        // Redirect based on role
        if ($user->role === 'admin') return redirect('/admin/dashboard');
        if ($user->role === 'worker') return redirect('/worker/dashboard');

        return redirect('/citizen/dashboard');
    }

    // Logout
    public function logout()
    {
        session()->flush();
        cookie()->queue(cookie()->forget('user_email'));

        return redirect('/login');
    }
}