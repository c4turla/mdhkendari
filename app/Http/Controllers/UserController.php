<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    // Tampilkan daftar user
    public function index(Request $request)
    {
        // Pencarian berdasarkan nama, email, atau username
        $search = $request->input('search');
    
        $users = User::where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->paginate(10); // Menampilkan 10 data per halaman
    
        // Menghitung total user
        $totalUsers = User::count();
    
        return view('user.index', compact('users', 'totalUsers'));
    }

    public function create()
    {
        return view('user.create');
    }

    // Tampilkan detail user
    public function show($id)
    {
        $user = User::findOrFail($id); // Cari user berdasarkan ID
        return view('user.show', compact('user'));
    }

    // Menyimpan user baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'user_level' => 'required|in:admin,staff,user',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'phone_number' => 'required|string|max:20',
            'address' => 'nullable|string',
        ]);

        $user = new User($validated);
        $user->password = bcrypt($request->password);

        if ($request->hasFile('photo')) {
            $filePath = $request->file('photo')->store('photos', 'public');
            $user->photo = $filePath;
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'Data Pengguna berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, $id)
    {
        // Validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8',
            'user_level' => 'required|in:admin,operator,sales',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'phone_number' => 'required|string|max:20',
            'address' => 'nullable|string',
        ]);

        $user = User::findOrFail($id);
        $user->fill($validated);

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // Update foto jika ada
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo) {
                \Storage::disk('public')->delete($user->photo);
            }

            $filePath = $request->file('photo')->store('photos', 'public');
            $user->photo = $filePath;
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'Data Pengguna berhasil diperbarui!');
    }

    // Hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('user.index')->with('success', 'Data Pengguna Berhasil dihapus!');
    }
}
