<?php
namespace App\Http\Controllers;

use App\Models\FasilitasUmum;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FasilitasUmumController extends Controller
{
    /* =========================
       INDEX
       ========================= */
    public function index(Request $request)
    {
        $query = FasilitasUmum::with('media');

        /* =====================
       SEARCH (NAMA / ALAMAT)
    ====================== */
        if ($request->filled('q')) {
            $q = $request->q;

            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', "%{$q}%")
                    ->orWhere('alamat', 'like', "%{$q}%");
            });
        }

        /* =====================
       FILTER JENIS
    ====================== */
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        /* =====================
       FILTER KAPASITAS MIN
    ====================== */
        if ($request->filled('kapasitas_min')) {
            $query->where('kapasitas', '>=', $request->kapasitas_min);
        }

        /* =====================
       SORTING
    ====================== */
        $sort = $request->get('sort', 'latest');

        if ($sort === 'terlama') {
            $query->oldest();
        } elseif ($sort === 'nama') {
            $query->orderBy('nama');
        } else {
            $query->latest();
        }

        $fasilitas = $query
            ->paginate(10)
            ->withQueryString(); // 🔥 biar filter tidak hilang saat pagination

        return view('fasilitas.index', compact('fasilitas'));
    }

    /* =========================
       CREATE
       ========================= */
    public function create()
    {
        return view('fasilitas.create');
    }

    /* =========================
       STORE
       ========================= */
    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'jenis'     => 'required|string|max:100',
            'alamat'    => 'nullable|string',
            'rt'        => 'nullable|string|max:3',
            'rw'        => 'nullable|string|max:3',
            'kapasitas' => 'nullable|integer|min:1',
            'deskripsi' => 'nullable|string',

            'foto.*'    => 'nullable|image|max:2048',
            'sop.*'     => 'nullable|mimes:pdf,doc,docx|max:4096',
        ]);

        $fasilitas = FasilitasUmum::create([
            'nama'      => $request->nama,
            'jenis'     => $request->jenis,
            'alamat'    => $request->alamat,
            'rt'        => $request->rt,
            'rw'        => $request->rw,
            'kapasitas' => $request->kapasitas,
            'deskripsi' => $request->deskripsi,
        ]);

        /* ===== SIMPAN FOTO ===== */
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $i => $file) {
                $path = $file->store('fasilitas/foto', 'public');

                Media::create([
                    'ref_table'  => 'fasilitas_umum',
                    'ref_id'     => $fasilitas->fasilitas_id,
                    'file_url'   => $path,
                    'mime_type'  => $file->getMimeType(),
                    'sort_order' => $i,
                ]);
            }
        }

        /* ===== SIMPAN SOP ===== */
        if ($request->hasFile('sop')) {
            foreach ($request->file('sop') as $i => $file) {
                $path = $file->store('fasilitas/sop', 'public');

                Media::create([
                    'ref_table'  => 'fasilitas_umum',
                    'ref_id'     => $fasilitas->fasilitas_id,
                    'file_url'   => $path,
                    'mime_type'  => $file->getMimeType(),
                    'sort_order' => $i,
                ]);
            }
        }

        return redirect()->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil ditambahkan');
    }

    /* =========================
       EDIT
       ========================= */
    public function edit(FasilitasUmum $fasilitas)
    {
        $fasilitas->load('media');
        return view('fasilitas.edit', compact('fasilitas'));
    }

    /* =========================
       UPDATE (FIX TOTAL)
       ========================= */
    public function update(Request $request, FasilitasUmum $fasilitas)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'jenis'     => 'required|string|max:100',
            'alamat'    => 'nullable|string',
            'rt'        => 'nullable|string|max:3',
            'rw'        => 'nullable|string|max:3',
            'kapasitas' => 'nullable|integer|min:1',
            'deskripsi' => 'nullable|string',

            'foto.*'    => 'nullable|image|max:2048',
            'sop.*'     => 'nullable|mimes:pdf,doc,docx|max:4096',
        ]);

        /* ===== UPDATE DATA ===== */
        $fasilitas->update([
            'nama'      => $request->nama,
            'jenis'     => $request->jenis,
            'alamat'    => $request->alamat,
            'rt'        => $request->rt,
            'rw'        => $request->rw,
            'kapasitas' => $request->kapasitas,
            'deskripsi' => $request->deskripsi,
        ]);

        /* ===== HAPUS MEDIA YANG DICENTANG ===== */
        if ($request->filled('hapus_media')) {
            $mediaList = Media::whereIn('media_id', $request->hapus_media)->get();

            foreach ($mediaList as $media) {
                if ($media->file_url && Storage::disk('public')->exists($media->file_url)) {
                    Storage::disk('public')->delete($media->file_url);
                }
                $media->delete();
            }
        }

        /* ===== TAMBAH FOTO BARU ===== */
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $i => $file) {
                $path = $file->store('fasilitas/foto', 'public');

                Media::create([
                    'ref_table'  => 'fasilitas_umum',
                    'ref_id'     => $fasilitas->fasilitas_id,
                    'file_url'   => $path,
                    'mime_type'  => $file->getMimeType(),
                    'sort_order' => $i,
                ]);
            }
        }

        /* ===== TAMBAH SOP BARU ===== */
        if ($request->hasFile('sop')) {
            foreach ($request->file('sop') as $i => $file) {
                $path = $file->store('fasilitas/sop', 'public');

                Media::create([
                    'ref_table'  => 'fasilitas_umum',
                    'ref_id'     => $fasilitas->fasilitas_id,
                    'file_url'   => $path,
                    'mime_type'  => $file->getMimeType(),
                    'sort_order' => $i,
                ]);
            }
        }

        return redirect()->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil diperbarui');
    }

    /* =========================
       DESTROY
       ========================= */
    public function destroy(FasilitasUmum $fasilitas)
    {
        foreach ($fasilitas->media as $media) {
            if ($media->file_url && Storage::disk('public')->exists($media->file_url)) {
                Storage::disk('public')->delete($media->file_url);
            }
            $media->delete();
        }

        $fasilitas->delete();

        return redirect()->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil dihapus');
    }
}
