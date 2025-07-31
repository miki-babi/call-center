<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Shop;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;



class SessionController extends Controller
{
    //
    
    public function login(Request $request)
    {
        // dd($request->all());
        $attribute = $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);
        // dd($attribute);

        try {
            if (Auth::attempt($attribute)) {
                Log::info('Login attempt successful for phone: ' . $attribute['phone']);
                $request->session()->regenerate();
                // dd('Login successful');
                return redirect()->route('order.index', ['page' => 1])->with('success', 'Login successful');
            } else {
                // dd('Login failed');
                Log::warning('Login attempt failed for phone: ' . $attribute['phone']);
            }
        } catch (\Exception $e) {
            dd('An error occurred during login: ' . $e->getMessage());
            Log::error('Login error: ' . $e->getMessage());
            return back()->withErrors(['phone' => 'An error occurred during login. Please try again.']);
        }

        return back()->withErrors(['phone' => 'Invalid credentials']);
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('auth.form');
    }


}
