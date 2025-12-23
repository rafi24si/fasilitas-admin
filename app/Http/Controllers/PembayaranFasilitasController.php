<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\PeminjamanFasilitas;
use App\Models\PembayaranFasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranFasilitasController extends Controller
{
    /* =========================
       INDEX
       ========================= */
    public function index()
    {
        $pembayaran = PembayaranFasilitas::with([
                'peminjaman.fasilitas',
                'peminjaman.warga',
                'media'
            ])
            ->latest()
            ->paginate(10);

        return view('pembayaran.index', compact('pembayaran'));
    }

    /* =========================
       CREATE
       ========================= */
    public function create()
    {
        $peminjaman = PeminjamanFasilitas::with(['fasilitas','warga'])
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        return view('pembayaran.create', compact('peminjaman'));
    }

    /* =========================
       STORE
       ========================= */
    public function store(Request $request)
    {
        $request->validate([
            'pinjam_id' => 'required|exists:peminjaman_fasilitas,pinjam_id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
            'metode' => 'required|string|max:50',
            'keterangan' => 'nullable|string',

            'resi.*' => 'nullable|mimes:jpg,jpeg,png,webp,pdf|max:4096',
        ]);

        $bayar = PembayaranFasilitas::create([
            'pinjam_id' => $request->pinjam_id,
            'tanggal' => $request->tanggal,
            'jumlah' => $request->jumlah,
            'metode' => $request->metode,
            'keterangan' => $request->keterangan,
        ]);

        /* ===== SIMPAN RESI ===== */
        if ($request->hasFile('resi')) {
            foreach ($request->file('resi') as $i => $file) {
                $path = $file->store('pembayaran/resi', 'public');

                Media::create([
                    'ref_table' => 'pembayaran_fasilitas',
                    'ref_id' => $bayar->bayar_id,
                    'file_url' => $path,
                    'caption' => 'Resi pembayaran',
                    'mime_type' => $file->getMimeType(),
                    'sort_order' => $i,
                ]);
            }
        }

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil disimpan');
    }

    /* =========================
       EDIT
       ========================= */
    public function edit(PembayaranFasilitas $pembayaran)
    {
        $pembayaran->load(['peminjaman','media']);
        $peminjaman = PeminjamanFasilitas::with(['fasilitas','warga'])->get();

        return view('pembayaran.edit', compact('pembayaran','peminjaman'));
    }

    /* =========================
       UPDATE
       ========================= */
    public function update(Request $request, PembayaranFasilitas $pembayaran)
    {
        $request->validate([
            'pinjam_id' => 'required|exists:peminjaman_fasilitas,pinjam_id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
            'metode' => 'required|string|max:50',
            'keterangan' => 'nullable|string',

            'resi.*' => 'nullable|mimes:jpg,jpeg,png,webp,pdf|max:4096',
            'hapus_media' => 'nullable|array',
            'hapus_media.*' => 'integer|exists:media,media_id',
        ]);

        $pembayaran->update([
            'pinjam_id' => $request->pinjam_id,
            'tanggal' => $request->tanggal,
            'jumlah' => $request->jumlah,
            'metode' => $request->metode,
            'keterangan' => $request->keterangan,
        ]);

        /* ===== HAPUS RESI ===== */
        if ($request->filled('hapus_media')) {
            $medias = Media::whereIn('media_id', $request->hapus_media)
                ->where('ref_table', 'pembayaran_fasilitas')
                ->where('ref_id', $pembayaran->bayar_id)
                ->get();

            foreach ($medias as $m) {
                if ($m->file_url && Storage::disk('public')->exists($m->file_url)) {
                    Storage::disk('public')->delete($m->file_url);
                }
                $m->delete();
            }
        }

        /* ===== TAMBAH RESI BARU ===== */
        if ($request->hasFile('resi')) {
            $start = (int) Media::where('ref_table', 'pembayaran_fasilitas')
                ->where('ref_id', $pembayaran->bayar_id)
                ->max('sort_order');

            $start = is_null($start) ? 0 : $start + 1;

            foreach ($request->file('resi') as $i => $file) {
                $path = $file->store('pembayaran/resi', 'public');

                Media::create([
                    'ref_table' => 'pembayaran_fasilitas',
                    'ref_id' => $pembayaran->bayar_id,
                    'file_url' => $path,
                    'caption' => 'Resi pembayaran',
                    'mime_type' => $file->getMimeType(),
                    'sort_order' => $start + $i,
                ]);
            }
        }

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil diperbarui');
    }

    /* =========================
       DESTROY
       ========================= */
    public function destroy(PembayaranFasilitas $pembayaran)
    {
        foreach ($pembayaran->media as $m) {
            if ($m->file_url && Storage::disk('public')->exists($m->file_url)) {
                Storage::disk('public')->delete($m->file_url);
            }
            $m->delete();
        }

        $pembayaran->delete();

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil dihapus');
    }
}
