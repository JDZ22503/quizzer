<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('student.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('student')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('student.subjects');
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }

    public function showRegister()
    {
        return view('student.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:students,email',
            'password'    => 'required|min:8|confirmed',
            'phone'       => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'school'      => 'nullable|string|max:255',
            'class'       => 'nullable|string|max:50',
            'roll_number' => 'nullable|string|max:50',
            'medium'      => 'required|in:english,gujarati',
        ]);

        $data['password'] = Hash::make($data['password']);
        $student = Student::create($data);

        Auth::guard('student')->login($student);
        $request->session()->regenerate();

        return redirect()->route('student.subjects');
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('student.login');
    }
}
