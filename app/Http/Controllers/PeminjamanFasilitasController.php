<?php
namespace App\Http\Controllers;

use App\Models\FasilitasUmum;
use App\Models\Media;
use App\Models\PeminjamanFasilitas;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PeminjamanFasilitasController extends Controller
{
    public function index(Request $request)
    {
        $query = PeminjamanFasilitas::with(['fasilitas', 'warga', 'media']);

        /* =====================
       SEARCH (NAMA WARGA / FASILITAS / TUJUAN)
    ====================== */
        if ($request->filled('q')) {
            $q = $request->q;

            $query->where(function ($sub) use ($q) {
                $sub->whereHas('warga', function ($w) use ($q) {
                    $w->where('nama', 'like', "%{$q}%");
                })
                    ->orWhereHas('fasilitas', function ($f) use ($q) {
                        $f->where('nama', 'like', "%{$q}%");
                    })
                    ->orWhere('tujuan', 'like', "%{$q}%");
            });
        }

        /* =====================
       FILTER STATUS
    ====================== */
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /* =====================
       FILTER TANGGAL
    ====================== */
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_mulai', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_selesai', '<=', $request->tanggal_selesai);
        }

        /* =====================
       SORTING
    ====================== */
        $sort = $request->get('sort', 'latest');

        if ($sort === 'terlama') {
            $query->oldest();
        } elseif ($sort === 'tanggal') {
            $query->orderBy('tanggal_mulai');
        } else {
            $query->latest();
        }

        $pinjam = $query
            ->paginate(10)
            ->withQueryString(); // 🔥 filter tetap saat pagination

        return view('peminjaman.index', compact('pinjam'));
    }

    public function create()
    {
        // biasanya dropdown pilih fasilitas + warga
        $fasilitas = FasilitasUmum::orderBy('nama')->get();
        $warga     = Warga::orderBy('nama')->get();

        return view('peminjaman.create', compact('fasilitas', 'warga'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fasilitas_id'    => 'required|exists:fasilitas_umum,fasilitas_id',
            'warga_id'        => 'required|exists:warga,warga_id',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'tujuan'          => 'nullable|string|max:255',
            'status'          => 'nullable|in:menunggu,disetujui,ditolak,selesai,batal',
            'total_biaya'     => 'nullable|numeric|min:0',

            // bukti bayar → media (boleh foto/pdf)
            'bukti_bayar.*'   => 'nullable|mimes:jpg,jpeg,png,webp,pdf|max:4096',
        ]);

        $pinjam = PeminjamanFasilitas::create([
            'fasilitas_id'    => $request->fasilitas_id,
            'warga_id'        => $request->warga_id,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'tujuan'          => $request->tujuan,
            'status'          => $request->status ?? 'menunggu',
            'total_biaya'     => $request->total_biaya ?? 0,
        ]);

        // Simpan bukti bayar ke media
        if ($request->hasFile('bukti_bayar')) {
            foreach ($request->file('bukti_bayar') as $i => $file) {
                $path = $file->store('peminjaman/bukti_bayar', 'public');

                Media::create([
                    'ref_table'  => 'peminjaman_fasilitas',
                    'ref_id'     => $pinjam->pinjam_id,
                    'file_url'   => $path,
                    'caption'    => 'Bukti bayar',
                    'mime_type'  => $file->getMimeType(),
                    'sort_order' => $i,
                ]);
            }
        }

        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil ditambahkan.');
    }

    public function edit(PeminjamanFasilitas $peminjaman)
    {
        $peminjaman->load(['fasilitas', 'warga', 'media']);
        $fasilitas = FasilitasUmum::orderBy('nama')->get();
        $warga     = Warga::orderBy('nama')->get();

        return view('peminjaman.edit', compact('peminjaman', 'fasilitas', 'warga'));
    }

    public function update(Request $request, PeminjamanFasilitas $peminjaman)
    {
        $request->validate([
            'fasilitas_id'    => 'required|exists:fasilitas_umum,fasilitas_id',
            'warga_id'        => 'required|exists:warga,warga_id',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'tujuan'          => 'nullable|string|max:255',
            'status'          => 'required|in:menunggu,disetujui,ditolak,selesai,batal',
            'total_biaya'     => 'nullable|numeric|min:0',

            'bukti_bayar.*'   => 'nullable|mimes:jpg,jpeg,png,webp,pdf|max:4096',
            'hapus_media'     => 'nullable|array',
            'hapus_media.*'   => 'integer|exists:media,media_id',
        ]);

        $peminjaman->update([
            'fasilitas_id'    => $request->fasilitas_id,
            'warga_id'        => $request->warga_id,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'tujuan'          => $request->tujuan,
            'status'          => $request->status,
            'total_biaya'     => $request->total_biaya ?? 0,
        ]);

        // Hapus bukti bayar yang dicentang
        if ($request->filled('hapus_media')) {
            $medias = Media::whereIn('media_id', $request->hapus_media)
                ->where('ref_table', 'peminjaman_fasilitas')
                ->where('ref_id', $peminjaman->pinjam_id)
                ->get();

            foreach ($medias as $m) {
                if ($m->file_url && Storage::disk('public')->exists($m->file_url)) {
                    Storage::disk('public')->delete($m->file_url);
                }
                $m->delete();
            }
        }

        // Tambah bukti bayar baru
        if ($request->hasFile('bukti_bayar')) {
            $startOrder = (int) Media::where('ref_table', 'peminjaman_fasilitas')
                ->where('ref_id', $peminjaman->pinjam_id)
                ->max('sort_order');

            $startOrder = is_null($startOrder) ? 0 : $startOrder + 1;

            foreach ($request->file('bukti_bayar') as $i => $file) {
                $path = $file->store('peminjaman/bukti_bayar', 'public');

                Media::create([
                    'ref_table'  => 'peminjaman_fasilitas',
                    'ref_id'     => $peminjaman->pinjam_id,
                    'file_url'   => $path,
                    'caption'    => 'Bukti bayar',
                    'mime_type'  => $file->getMimeType(),
                    'sort_order' => $startOrder + $i,
                ]);
            }
        }

        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil diperbarui.');
    }

    public function destroy(PeminjamanFasilitas $peminjaman)
    {
        // Hapus semua media bukti bayar
        foreach ($peminjaman->media as $m) {
            if ($m->file_url && Storage::disk('public')->exists($m->file_url)) {
                Storage::disk('public')->delete($m->file_url);
            }
            $m->delete();
        }

        $peminjaman->delete();

        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil dihapus.');
    }
}
