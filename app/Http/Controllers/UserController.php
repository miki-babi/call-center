<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;




class UserController extends Controller
{
 
    

    // Handle logout
    
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',
            // 'role' => 'required|in:farmer,admin'
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password)
        ]);

        Auth::login($user);
        return redirect()->route('dashboard');
    }
}
