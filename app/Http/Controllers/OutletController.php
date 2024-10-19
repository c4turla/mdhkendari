<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\User;
use App\Models\Zona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OutletController extends Controller
{
    public function index(Request $request)
    {
        // Ambil input pencarian
        $search = $request->input('search');

        // Jika ada input pencarian, cari berdasarkan nama atau nama pemilik
        if ($search) {
            $outlets = Outlet::where('nama', 'like', "%{$search}%")
                            ->orWhere('nama_pemilik', 'like', "%{$search}%")
                            ->paginate(10);
        } else {
            // Jika tidak ada input pencarian, ambil semua data
            $outlets = Outlet::paginate(10);
        }
        return view('outlets.index', compact('outlets'));
    }
    public function show(){
        // Ambil semua data outlet termasuk latitude dan longitude
        $outlets = Outlet::select('nama', 'latitude', 'longitude')->get();

        // Kirim data outlet ke view
        return view('outlets.map', compact('outlets'));
        
    }

    public function create()
    {
        $sales = User::where('user_level', 'sales')->pluck('full_name', 'id');
        $zonas = Zona::pluck('nama', 'id_zona');
        return view('outlets.create', compact('sales', 'zonas'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nama_pemilik' => 'required|string|max:255',
            'NIK' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'id_sales' => 'required|exists:users,id', // Assuming 'id' is the primary key in users table
            'id_zona' => 'required|exists:zonas,id_zona',
            'alamat' => 'required|string|max:500',
            'ktp' => 'nullable|image|mimes:jpeg,png,jpg,gif,bmp,tiff|max:2048', // Optional and image validation
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Handle file upload
        $ktpPath = null;
        if ($request->hasFile('ktp')) {
            $ktpFile = $request->file('ktp');
            $ktpPath = $ktpFile->store('ktp', 'public');
        }

        // Create a new outlet record
        $outlet = Outlet::create([
            'nama' => $request->input('nama'),
            'nama_pemilik' => $request->input('nama_pemilik'),
            'NIK' => $request->input('NIK'),
            'phone' => $request->input('phone'),
            'id_sales' => $request->input('id_sales'),
            'id_zona' => $request->input('id_zona'),
            'alamat' => $request->input('alamat'),
            'ktp' => $ktpPath,
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
        ]);

        // Redirect or return response
        return redirect()->route('outlets.index')->with('success', 'Outlet created successfully.');
    }

    public function edit($id)
    {
        $outlet = Outlet::findOrFail($id);
        $sales = User::where('user_level', 'sales')->pluck('full_name', 'id');
        $zonas = Zona::pluck('nama', 'id_zona');
        return view('outlets.edit', compact('outlet', 'sales', 'zonas'));
    }

    public function update(Request $request, Outlet $outlet)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'nama_pemilik' => 'required|string|max:255',
            'NIK' => 'required|numeric',
            'phone' => 'required|numeric',
            'id_sales' => 'required|exists:users,id',
            'id_zona' => 'required|exists:zonas,id_zona',
            'alamat' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'ktp' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Validasi gambar KTP
        ]);

        // Update data outlet
        $outlet->nama = $request->input('nama');
        $outlet->nama_pemilik = $request->input('nama_pemilik');
        $outlet->NIK = $request->input('NIK');
        $outlet->phone = $request->input('phone');
        $outlet->id_sales = $request->input('id_sales');
        $outlet->id_zona = $request->input('id_zona');
        $outlet->alamat = $request->input('alamat');
        $outlet->latitude = $request->input('latitude');
        $outlet->longitude = $request->input('longitude');

        // Cek apakah ada file KTP yang diupload
        if ($request->hasFile('ktp')) {
            // Hapus file KTP lama jika ada
            if ($outlet->ktp && \Storage::disk('public')->exists($outlet->ktp)) {
                \Storage::disk('public')->delete($outlet->ktp);
            }

            // Simpan file KTP baru
            $ktpFile = $request->file('ktp');
            $ktpPath = $ktpFile->store('ktp', 'public');

            // Simpan path KTP di database
            $outlet->ktp = $ktpPath;
        }

        // Simpan perubahan data outlet
        $outlet->save();

        // Redirect dengan pesan sukses
        return redirect()->route('outlets.index')->with('success', 'Data outlet berhasil diupdate.');
    }

    
    public function destroy(Outlet $outlet)
    {
        if ($outlet->ktp) {
            Storage::delete($outlet->ktp);
        }

        $outlet->delete();

        return redirect()->route('outlets.index')->with('success', 'Outlet deleted successfully.');
    }
}
