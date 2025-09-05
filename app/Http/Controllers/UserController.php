<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Update profile pengguna yang sedang login
     */
    public function updateProfile(Request $request)
    {
        try {
            // Validasi input
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . Auth::id(), // Pastikan email milik user yang sedang login
                'password' => 'nullable|confirmed|min:6', // Password bisa kosong, jika diisi harus valid
                'phone' => 'nullable|string|max:15',
            ]);

            $user = Auth::user(); // Ambil user yang sedang login

            // Update data
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->phone = $data['phone'];

            // Update password jika diisi
            if ($request->has('password') && $data['password']) {
                $user->password = Hash::make($data['password']); // Jika password diisi, update password
            }

            $user->save();

            return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error('User profile update error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memperbarui profil.');
        }
    }

    /**
     * Menambah user baru (Admin)
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Menyimpan user baru (Admin)
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed|min:6',
                'role' => 'required|in:admin,staff,driver,guide', // Pastikan role valid
            ]);

            // Simpan user baru
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
            ]);

            return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
        } catch (\Throwable $e) {
            Log::error('Create user error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal menambah user.');
        }
    }

    /**
     * Menampilkan halaman daftar user (admin)
     */
    public function index()
    {
        $users = User::paginate(15);
        return view('users.index', compact('users'));
    }

    /**
     * Menampilkan halaman edit user (admin)
     */
    public function edit(User $user)
    {

        return view('users.edit', compact('user'));
    }

    /**
     * Memperbarui data user (admin)
     */
    public function update(Request $request, User $user)
    {
        try {
            // Validasi input
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id, // Pastikan email tidak duplicate
                'password' => 'nullable|confirmed|min:6',
                'role' => 'required|in:admin,staff,driver,guide',
            ]);

            // Update user data
            $user->name = $data['name'];
            $user->email = $data['email'];

            // Update password jika diisi
            if ($request->has('password') && !empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            $user->role = $data['role'];
            $user->save();

            return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error('Update user error', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memperbarui user.');
        }
    }

    /**
     * Menampilkan halaman profil user yang sedang login
     */
    public function showProfile()
    {
        $user = Auth::user();
        return view('users.profile', compact('user'));
    }
}
