<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alat;
use App\Models\Kategori;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class AlatController extends Controller
{
    public function alat(Request $request)
    {
        $query = Alat::query();

        if ($request->filled('search')) {
            $query->where('nama_alat', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori') && $request->kategori != 'Semua') {
            $query->where('kategori', $request->kategori);
        }

        $alats = $query->latest()->get();
        $kategoris = Kategori::orderBy('nama')->get();

        return view('admin.alat', compact('alats', 'kategoris'));
    }

    public function storeAlat(Request $request)
    {
        $request->validate([
            'nama_alat' => 'required',
            'kategori' => 'required',
            'stok_total' => 'required|numeric',
            'harga_sewa' => 'required|numeric',
            'kondisi' => 'required',
            'foto' => 'nullable|image|max:2048'
        ]);

        $path = $request->hasFile('foto') ? $request->file('foto')->store('alats', 'public') : null;
        
        // Cari kategori_id berdasarkan nama kategori
        $kategoriModel = Kategori::where('nama', $request->kategori)->first();

        Alat::create([
            'nama_alat' => $request->nama_alat,
            'slug' => \Illuminate\Support\Str::slug($request->nama_alat) . '-' . \Illuminate\Support\Str::random(5),
            'kategori' => $request->kategori,
            'kategori_id' => $kategoriModel ? $kategoriModel->id : null,
            'stok_total' => $request->stok_total,
            'stok_tersedia' => $request->stok_total,
            'harga_sewa' => $request->harga_sewa,
            'kondisi' => $request->kondisi,
            'deskripsi' => $request->deskripsi,
            'foto' => $path,
        ]);

        return back()->with('success', 'Alat berhasil ditambah!');
    }

    public function update(Request $request, $id)
    {
        $alat = Alat::findOrFail($id);
        
        $request->validate([
            'nama_alat' => 'required',
            'stok_total' => 'required|numeric',
            'harga_sewa' => 'required|numeric',
            'kondisi' => 'required'
        ]);

        if ($request->hasFile('foto')) {
            if ($alat->foto) Storage::disk('public')->delete($alat->foto);
            $alat->foto = $request->file('foto')->store('alats', 'public');
        }

        // Cari kategori_id berdasarkan nama kategori
        $kategoriModel = Kategori::where('nama', $request->kategori)->first();

        $alat->update([
            'nama_alat' => $request->nama_alat,
            'kategori' => $request->kategori,
            'kategori_id' => $kategoriModel ? $kategoriModel->id : null,
            'stok_total' => $request->stok_total,
            'stok_tersedia' => $request->stok_total,
            'harga_sewa' => $request->harga_sewa,
            'kondisi' => $request->kondisi,
            'deskripsi' => $request->deskripsi,
        ]);

        return back()->with('success', 'Data diperbarui!');
    }

    public function exportPdf(Request $request)
    {
        $query = Alat::query();

        if ($request->filled('search')) {
            $query->where('nama_alat', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori') && $request->kategori != 'Semua') {
            $query->where('kategori', $request->kategori);
        }

        $alats = $query->latest()->get();
        $totalAlat = $alats->count();
        $totalUnit = $alats->sum('stok_total');

        $pdf = Pdf::loadView('admin.alat_pdf', compact('alats', 'totalAlat', 'totalUnit'));
        return $pdf->download('laporan-katalog-alat-' . now()->format('Y-m-d') . '.pdf');
    }

    public function destroy($id)
    {
        $alat = Alat::findOrFail($id);

        if ($alat->foto) {
            Storage::disk('public')->delete($alat->foto);
        }

        $alat->delete();

        return redirect()->back()->with('success', 'Peralatan berhasil dihapus dari katalog.');
    }
}