@extends('layouts.admin.app')
@section('title', 'Edit Fasilitas')

@push('styles')
<style>
    label { font-weight:600; }

    .media-box {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 10px;
        text-align: center;
        position: relative;
    }

    .media-thumb {
        width: 100%;
        height: 90px;
        object-fit: cover;
        border-radius: 8px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid" style="padding-top:35px;">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between mb-4">
        <h3 class="fw-bold text-primary">✏️ Edit Fasilitas</h3>
        <a href="{{ route('fasilitas.index') }}" class="btn btn-secondary px-4">
            ← Kembali
        </a>
    </div>

    <div class="card shadow-sm p-4">

        {{-- 🔥 FIX DI SINI --}}
        <form method="POST"
              action="{{ route('fasilitas.update', $fasilitas) }}"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- ================= DATA UTAMA ================= --}}
            <div class="row g-3 mb-4">

                <div class="col-md-6">
                    <label>Nama Fasilitas</label>
                    <input type="text"
                           name="nama"
                           class="form-control"
                           value="{{ old('nama', $fasilitas->nama) }}"
                           required>
                </div>

                <div class="col-md-6">
                    <label>Jenis</label>
                    <input type="text"
                           name="jenis"
                           class="form-control"
                           value="{{ old('jenis', $fasilitas->jenis) }}"
                           required>
                </div>

                <div class="col-md-12">
                    <label>Alamat</label>
                    <textarea name="alamat"
                              class="form-control"
                              rows="2">{{ old('alamat', $fasilitas->alamat) }}</textarea>
                </div>

                <div class="col-md-2">
                    <label>RT</label>
                    <input type="text"
                           name="rt"
                           class="form-control"
                           value="{{ old('rt', $fasilitas->rt) }}">
                </div>

                <div class="col-md-2">
                    <label>RW</label>
                    <input type="text"
                           name="rw"
                           class="form-control"
                           value="{{ old('rw', $fasilitas->rw) }}">
                </div>

                <div class="col-md-4">
                    <label>Kapasitas</label>
                    <input type="number"
                           name="kapasitas"
                           class="form-control"
                           value="{{ old('kapasitas', $fasilitas->kapasitas) }}">
                </div>

                <div class="col-md-4">
                    <label>Deskripsi</label>
                    <input type="text"
                           name="deskripsi"
                           class="form-control"
                           value="{{ old('deskripsi', $fasilitas->deskripsi) }}">
                </div>

            </div>

            {{-- ================= MEDIA LAMA ================= --}}
            <h6 class="fw-bold mb-2">🗂️ Media Tersimpan</h6>

            <div class="row g-3 mb-4">
                @forelse ($fasilitas->media as $m)
                    <div class="col-md-3">
                        <div class="media-box">

                            {{-- CHECKBOX HAPUS --}}
                            <div class="form-check mb-1">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="hapus_media[]"
                                       value="{{ $m->media_id }}">
                                <label class="form-check-label small text-danger">
                                    Hapus
                                </label>
                            </div>

                            {{-- PREVIEW --}}
                            @if (str_starts_with($m->mime_type, 'image'))
                                <img src="{{ asset('storage/'.$m->file_url) }}"
                                     class="media-thumb">
                            @else
                                <i class="ti ti-file-text fs-1 text-secondary"></i>
                                <div class="small">Dokumen SOP</div>
                            @endif

                        </div>
                    </div>
                @empty
                    <div class="col-12 text-muted">
                        Belum ada media
                    </div>
                @endforelse
            </div>

            {{-- ================= MEDIA BARU ================= --}}
            <h6 class="fw-bold mb-2">➕ Tambah Media</h6>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label>Foto Fasilitas</label>
                    <input type="file"
                           name="foto[]"
                           class="form-control"
                           accept="image/*"
                           multiple>
                </div>

                <div class="col-md-6">
                    <label>Dokumen SOP</label>
                    <input type="file"
                           name="sop[]"
                           class="form-control"
                           accept=".pdf,.doc,.docx"
                           multiple>
                </div>
            </div>

            {{-- ACTION --}}
            <div class="text-end">
                <button class="btn btn-primary px-5">
                    💾 Simpan Perubahan
                </button>
            </div>

        </form>

    </div>
</div>
@endsection
