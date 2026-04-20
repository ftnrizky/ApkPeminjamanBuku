<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::orderBy('created_at', 'desc')->get();
        return view('admin.kategori', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50|unique:kategoris',
            'icon' => 'required|string',
            'deskripsi' => 'nullable|string|max:200',
            'warna' => 'required|string'
        ]);

        Kategori::create($validated);

        return redirect()->route('admin.kategori')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:50|unique:kategoris,nama,' . $id,
            'icon' => 'required|string',
            'deskripsi' => 'nullable|string|max:200',
            'warna' => 'required|string'
        ]);

        $kategori->update($validated);

        return redirect()->route('admin.kategori')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        
        // Cek apakah kategori masih digunakan
        if ($kategori->alats()->exists()) {
            return redirect()->route('admin.kategori')->with('error', 'Kategori masih digunakan oleh alat. Hapus alat terlebih dahulu!');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori')->with('success', 'Kategori berhasil dihapus!');
    }
}
