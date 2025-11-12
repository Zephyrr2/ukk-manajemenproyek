<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function tampilUser(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Role filter
        if ($request->has('role') && $request->role != '' && $request->role != 'all') {
            $query->where('role', $request->role);
        }

        // Status filter
        if ($request->has('status') && $request->status != '' && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        // Append query parameters to pagination links
        $users->appends($request->except('page'));

        return view('pages.admin.users', compact('users'));
    }

    public function tambahUser()
    {
        return view('pages.admin.create-user');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'nullable|in:admin,leader,user',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role ?? 'user', // Default role is 'user'
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,leader,user',
        ]);

        // Prevent changing own role if current user is admin
        if ($user->id === Auth::id() && $request->role !== 'admin') {
            return redirect()->route('admin.users')->with('error', 'You cannot change your own admin role!');
        }

        $user->update([
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users')->with('success', 'User role updated successfully!');
    }

    public function edit(User $user)
    {
        return view('pages.admin.edit-user', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,leader,user',
        ]);

        $user->update($request->only(['name', 'email', 'role']));

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        // Prevent deleting the current logged-in user
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users')->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }
}
