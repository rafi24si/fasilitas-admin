<?php

namespace App\Http\Controllers;

use App\Models\PetugasFasilitas;
use App\Models\FasilitasUmum;
use App\Models\Warga;
use Illuminate\Http\Request;

class PetugasFasilitasController extends Controller
{
    /* =========================
       INDEX
    ========================== */
    public function index()
    {
        $petugas = PetugasFasilitas::with(['fasilitas','warga'])
                    ->latest()
                    ->paginate(10);

        return view('petugas.index', compact('petugas'));
    }

    /* =========================
       CREATE
    ========================== */
    public function create()
    {
        return view('petugas.create', [
            'fasilitas' => FasilitasUmum::orderBy('nama')->get(),
            'warga'     => Warga::orderBy('nama')->get(),
        ]);
    }

    /* =========================
       STORE
    ========================== */
    public function store(Request $request)
    {
        $request->validate([
            'fasilitas_id'       => 'required|exists:fasilitas_umum,fasilitas_id',
            'petugas_warga_id'   => 'required|exists:warga,warga_id',
            'peran'              => 'required|string|max:100',
        ]);

        PetugasFasilitas::create($request->only([
            'fasilitas_id',
            'petugas_warga_id',
            'peran',
        ]));

        return redirect()->route('petugas.index')
            ->with('success', 'Petugas fasilitas berhasil ditambahkan');
    }

    /* =========================
       EDIT
    ========================== */
    public function edit(PetugasFasilitas $petugas)
    {
        return view('petugas.edit', [
            'petugas'   => $petugas,
            'fasilitas' => FasilitasUmum::orderBy('nama')->get(),
            'warga'     => Warga::orderBy('nama')->get(),
        ]);
    }

    /* =========================
       UPDATE
    ========================== */
    public function update(Request $request, PetugasFasilitas $petugas)
    {
        $request->validate([
            'fasilitas_id'       => 'required|exists:fasilitas_umum,fasilitas_id',
            'petugas_warga_id'   => 'required|exists:warga,warga_id',
            'peran'              => 'required|string|max:100',
        ]);

        $petugas->update($request->only([
            'fasilitas_id',
            'petugas_warga_id',
            'peran',
        ]));

        return redirect()->route('petugas.index')
            ->with('success', 'Petugas fasilitas berhasil diperbarui');
    }

    /* =========================
       DESTROY
    ========================== */
    public function destroy(PetugasFasilitas $petugas)
    {
        $petugas->delete();

        return back()->with('success', 'Petugas fasilitas berhasil dihapus');
    }
}
