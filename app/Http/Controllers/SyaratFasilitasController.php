<?php
namespace App\Http\Controllers;

use App\Models\SyaratFasilitas;
use App\Models\FasilitasUmum;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SyaratFasilitasController extends Controller
{
    /* =========================
       INDEX
    ========================== */
    public function index()
    {
        $syarat = SyaratFasilitas::with(['fasilitas','media'])
                    ->latest()
                    ->paginate(10);

        return view('syarat.index', compact('syarat'));
    }

    /* =========================
       CREATE
    ========================== */
    public function create()
    {
        $fasilitas = FasilitasUmum::orderBy('nama')->get();
        return view('syarat.create', compact('fasilitas'));
    }

    /* =========================
       STORE
    ========================== */
    public function store(Request $request)
    {
        $request->validate([
            'fasilitas_id' => 'required|exists:fasilitas_umum,fasilitas_id',
            'nama_syarat'  => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'dokumen.*'    => 'nullable|mimes:pdf,doc,docx|max:4096',
        ]);

        $syarat = SyaratFasilitas::create(
            $request->only(['fasilitas_id','nama_syarat','deskripsi'])
        );

        /* SIMPAN DOKUMEN */
        if ($request->hasFile('dokumen')) {
            foreach ($request->file('dokumen') as $i => $file) {
                $path = $file->store('syarat_fasilitas', 'public');

                Media::create([
                    'ref_table'  => 'syarat_fasilitas',
                    'ref_id'     => $syarat->syarat_id,
                    'file_url'   => $path,
                    'mime_type'  => $file->getMimeType(),
                    'sort_order' => $i,
                ]);
            }
        }

        return redirect()->route('syarat.index')
            ->with('success', 'Syarat fasilitas berhasil ditambahkan');
    }

    /* =========================
       EDIT
    ========================== */
    public function edit(SyaratFasilitas $syarat)
    {
        $fasilitas = FasilitasUmum::orderBy('nama')->get();
        return view('syarat.edit', compact('syarat','fasilitas'));
    }

    /* =========================
       UPDATE
    ========================== */
    public function update(Request $request, SyaratFasilitas $syarat)
    {
        $request->validate([
            'fasilitas_id' => 'required|exists:fasilitas_umum,fasilitas_id',
            'nama_syarat'  => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'dokumen.*'    => 'nullable|mimes:pdf,doc,docx|max:4096',
        ]);

        $syarat->update(
            $request->only(['fasilitas_id','nama_syarat','deskripsi'])
        );

        /* HAPUS DOKUMEN */
        if ($request->hapus_media) {
            foreach ($syarat->media()->whereIn('media_id', $request->hapus_media)->get() as $m) {
                Storage::disk('public')->delete($m->file_url);
                $m->delete();
            }
        }

        /* TAMBAH DOKUMEN BARU */
        if ($request->hasFile('dokumen')) {
            foreach ($request->file('dokumen') as $i => $file) {
                $path = $file->store('syarat_fasilitas', 'public');

                Media::create([
                    'ref_table'  => 'syarat_fasilitas',
                    'ref_id'     => $syarat->syarat_id,
                    'file_url'   => $path,
                    'mime_type'  => $file->getMimeType(),
                    'sort_order' => $i,
                ]);
            }
        }

        return redirect()->route('syarat.index')
            ->with('success', 'Syarat fasilitas berhasil diperbarui');
    }

    /* =========================
       DESTROY
    ========================== */
    public function destroy(SyaratFasilitas $syarat)
    {
        foreach ($syarat->media as $m) {
            Storage::disk('public')->delete($m->file_url);
            $m->delete();
        }

        $syarat->delete();

        return back()->with('success', 'Syarat fasilitas berhasil dihapus');
    }
}
