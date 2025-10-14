<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show registration form
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Process registration
     */
    public function registerProcess(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $name = htmlspecialchars($request->input('name'));
        $email = htmlspecialchars($request->input('email'));
        $password = htmlspecialchars($request->input('password'));

        $hashedPassword = Hash::make($password);

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = $hashedPassword;
        $user->role = 'user'; // Default role
        $user->status = 'free'; // Default status
        $user->save();

        // Automatically log in the user after registration
        Auth::login($user);

        // Redirect based on role (same as login logic)
        if ($user->role == 'admin') {
            return redirect('/admin/dashboard')->with('success', 'Registration successful! Welcome to the admin dashboard.');
        } elseif ($user->role == 'leader') {
            return redirect('/leader/dashboard')->with('success', 'Registration successful! Welcome to the leader dashboard.');
        } else {
            return redirect('/user/dashboard')->with('success', 'Registration successful! Welcome to the dashboard.');
        }
    }

    /**
     * Show login form
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Process login
     */
    public function loginProcess(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = htmlspecialchars($request->input('email'));
        $password = htmlspecialchars($request->input('password'));

        $user = User::where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user);

            // Redirect based on role
            if ($user->role == 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role == 'leader') {
                return redirect()->route('leader.dashboard');
            } elseif ($user->role == 'user') {
                return redirect()->route('user.dashboard');
            }
        }

        return redirect('/login')->with('error', 'Invalid email or password');
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
