<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('teacher.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('teacher')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('teacher.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }

    public function showRegister()
    {
        return view('teacher.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'                    => 'required|string|max:255',
            'email'                   => 'required|email|unique:teachers,email',
            'password'                => 'required|min:8|confirmed',
            'phone'                   => 'nullable|string|max:20',
            'subject_specialization'  => 'nullable|string|max:255',
        ]);

        $data['password'] = Hash::make($data['password']);
        $teacher = Teacher::create($data);

        Auth::guard('teacher')->login($teacher);
        $request->session()->regenerate();

        return redirect()->route('teacher.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('teacher')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('teacher.login');
    }
}
