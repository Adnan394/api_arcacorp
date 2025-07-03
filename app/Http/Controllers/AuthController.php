<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);
    
        $user = \App\Models\User::where('email', $request->email)->first();
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials do not match our records.',
            ], 401);
        }
    
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'success' => true,
            'data' => [
                'userdata' => [
                    'role' => $user->role->name,
                    'username' => $user->username,
                ],
                'token' => $token,
            ],
        ]);
    }
    // public function login() {
    //     return view('auth.login');
    // }

    // public function login_store(Request $request) {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|min:8',
    //     ]);

    //     if (Auth::attempt($request->only('email', 'password'))) {
    //         return redirect('/dashboard')->with('success', 'Login Berhasil!');
    //     } else {
    //         return back()->withErrors([
    //             'email' => 'The provided credentials do not match our records.',
    //             'password' => 'The provided credentials do not match our records.',
    //         ]);
    //     }
    // } 

    // public function logout(Request $request) {
    //     Auth::logout();
    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();
    //     return redirect('/login');
    // }
}