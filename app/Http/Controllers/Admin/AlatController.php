<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use App\Models\Alat;
use App\Models\Kategori;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AlatController extends Controller
{
    public function alat(Request $request)
    {
        $query = Alat::query()->with('kategori');

        // 🔍 Search
        if ($request->filled('search')) {
            $query->where('nama_alat', 'like', '%' . $request->search . '%');
        }

        // 🔥 Filter berdasarkan kategori_id (RELASI)
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $alats = $query->latest()->get();
        $kategoris = Kategori::orderBy('nama')->get();

        return view('admin.alat', compact('alats', 'kategoris'));
    }

    public function storeAlat(Request $request)
    {
        $request->validate([
            'nama_alat'   => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'stok_total'  => 'required|numeric|min:1',
            'harga_sewa'  => 'required|numeric|min:0',
            'kondisi'     => 'required',
            'foto'        => 'nullable|image|max:2048',
        ]);

        $path = $request->hasFile('foto')
            ? $request->file('foto')->store('alats', 'public')
            : null;

        $alat = Alat::create([
            'nama_alat'     => $request->nama_alat,
            'slug'          => Str::slug($request->nama_alat) . '-' . Str::random(5),
            'kategori_id'   => $request->kategori_id,
            'stok_total'    => $request->stok_total,
            'stok_tersedia' => $request->stok_total,
            'harga_sewa'    => $request->harga_sewa,
            'kondisi'       => $request->kondisi,
            'deskripsi'     => $request->deskripsi,
            'foto'          => $path,
        ]);

        ActivityLogger::tambahAlat($alat);

        return back()->with('success', 'Alat berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $alat = Alat::findOrFail($id);

        $request->validate([
            'nama_alat'   => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'stok_total'  => 'required|numeric|min:1',
            'harga_sewa'  => 'required|numeric|min:0',
            'kondisi'     => 'required',
            'foto'        => 'nullable|image|max:2048',
        ]);

        // 📸 Update foto jika ada
        if ($request->hasFile('foto')) {
            if ($alat->foto) {
                Storage::disk('public')->delete($alat->foto);
            }
            $alat->foto = $request->file('foto')->store('alats', 'public');
        }

        $alat->update([
            'nama_alat'     => $request->nama_alat,
            'kategori_id'   => $request->kategori_id,
            'stok_total'    => $request->stok_total,
            'stok_tersedia' => $request->stok_total,
            'harga_sewa'    => $request->harga_sewa,
            'kondisi'       => $request->kondisi,
            'deskripsi'     => $request->deskripsi,
        ]);

        ActivityLogger::editAlat($alat);

        return back()->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $alat = Alat::findOrFail($id);

        // 🗑️ Hapus foto jika ada
        if ($alat->foto) {
            Storage::disk('public')->delete($alat->foto);
        }

        ActivityLogger::hapusAlat($alat);

        $alat->delete();

        return back()->with('success', 'Data berhasil dihapus!');
    }

    public function exportPdf(Request $request)
    {
        $query = Alat::query()->with('kategori');

        if ($request->filled('search')) {
            $query->where('nama_alat', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $alats = $query->latest()->get();
        $totalAlat = $alats->count();
        $totalUnit = $alats->sum('stok_total');

        $pdf = Pdf::loadView('admin.alat_pdf', compact('alats', 'totalAlat', 'totalUnit'));

        return $pdf->download('laporan-alat-' . now()->format('Y-m-d') . '.pdf');
    }
}