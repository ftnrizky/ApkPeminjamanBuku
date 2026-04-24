<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::withCount('alats')
            ->latest()
            ->get();

        return view('admin.kategori', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50|unique:kategoris,nama',
            'icon' => 'required|string',
            'deskripsi' => 'nullable|string|max:200',
            'warna' => 'required|string'
        ]);

        Kategori::create($validated);

        return back()->with('success', 'Kategori berhasil ditambahkan!');
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

        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);

        if ($kategori->alats()->exists()) {
            return back()->with('error', 'Kategori masih digunakan oleh alat!');
        }

        $kategori->delete();

        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}