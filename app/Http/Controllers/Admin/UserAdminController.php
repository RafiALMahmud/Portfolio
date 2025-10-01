<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserAdminController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'present_address' => 'required|string|max:500',
            'role' => 'required|in:user,admin',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'present_address' => $data['present_address'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')->with('status', 'User created successfully');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Only super admin can manage admins
        if ($user->role === 'admin' && auth()->user()->email !== 'rafi.almahmud.007@gmail.com') {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20|unique:users,phone,'.$user->id,
            'present_address' => 'nullable|string|max:500',
            'role' => 'required|in:user,admin',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        return redirect()->route('admin.users.index')->with('status', 'User updated');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin' && auth()->user()->email !== 'rafi.almahmud.007@gmail.com') {
            abort(403);
        }
        $user->delete();
        return back()->with('status', 'User deleted');
    }

    public function sendMessage(Request $request, User $user)
    {
        $data = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Prevent admin from messaging themselves
        if (auth()->id() === $user->id) {
            return back()->with('status', 'You cannot send a message to yourself');
        }

        $user->notify(new \App\Notifications\AdminMessage($data['message'], auth()->user()));

        return back()->with('status', 'Message sent successfully');
    }
}
