@extends('layouts.admin.app')
@section('title', 'Tambah Fasilitas Umum')

@push('styles')
<style>
    .fade-in {
        animation: fadeIn .35s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity:0; transform:translateY(12px); }
        to { opacity:1; transform:none; }
    }

    label { font-weight:600; }

    .section-card {
        border-radius: 16px;
        border: 1px solid #eef2f7;
        padding: 20px;
        margin-bottom: 22px;
        background: #fff;
    }

    .section-title {
        font-weight: 700;
        color: #0d6efd;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .hint {
        font-size: 12px;
        color: #6c757d;
    }

    .file-box {
        border: 2px dashed #dbe3f0;
        border-radius: 14px;
        padding: 18px;
        text-align: center;
        transition: .2s;
        background: #fafcff;
    }

    .file-box:hover {
        border-color: #0d6efd;
        background: #f4f8ff;
    }
</style>
@endpush

@section('content')
<div class="container-fluid fade-in" style="padding-top:35px; max-width:1200px;">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0">➕ Tambah Fasilitas Umum</h3>
            <small class="text-muted">Input data fasilitas desa</small>
        </div>

        <a href="{{ route('fasilitas.index') }}"
           class="btn btn-outline-secondary rounded-pill px-4">
            ← Kembali
        </a>
    </div>

    {{-- FORM --}}
    <form action="{{ route('fasilitas.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        {{-- ===================== DATA UTAMA ===================== --}}
        <div class="section-card shadow-sm">

            <div class="section-title">
                🏢 Informasi Fasilitas
            </div>

            <div class="row g-3">

                <div class="col-md-6">
                    <label>Nama Fasilitas</label>
                    <input type="text" name="nama"
                           class="form-control"
                           placeholder="Contoh: Aula Desa"
                           value="{{ old('nama') }}" required>
                </div>

                <div class="col-md-6">
                    <label>Jenis Fasilitas</label>
                    <input type="text" name="jenis"
                           class="form-control"
                           placeholder="Aula / Lapangan / Balai Desa"
                           value="{{ old('jenis') }}" required>
                </div>

                <div class="col-md-12">
                    <label>Alamat</label>
                    <textarea name="alamat"
                              class="form-control"
                              rows="2"
                              placeholder="Alamat lengkap fasilitas">{{ old('alamat') }}</textarea>
                </div>

                <div class="col-md-2">
                    <label>RT</label>
                    <input type="text" name="rt"
                           class="form-control"
                           maxlength="3"
                           value="{{ old('rt') }}">
                </div>

                <div class="col-md-2">
                    <label>RW</label>
                    <input type="text" name="rw"
                           class="form-control"
                           maxlength="3"
                           value="{{ old('rw') }}">
                </div>

                <div class="col-md-4">
                    <label>Kapasitas</label>
                    <input type="number" name="kapasitas"
                           class="form-control"
                           min="1"
                           placeholder="Jumlah orang"
                           value="{{ old('kapasitas') }}">
                </div>

                <div class="col-md-4">
                    <label>Deskripsi</label>
                    <input type="text" name="deskripsi"
                           class="form-control"
                           placeholder="Keterangan singkat"
                           value="{{ old('deskripsi') }}">
                </div>

            </div>
        </div>

        {{-- ===================== MEDIA ===================== --}}
        <div class="section-card shadow-sm">

            <div class="section-title">
                🖼️ Media Fasilitas
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <label>Foto Fasilitas</label>
                    <div class="file-box mt-1">
                        <input type="file" name="foto[]"
                               class="form-control"
                               accept="image/*"
                               multiple>
                        <div class="hint mt-2">
                            JPG / PNG • Maks 2MB • Bisa lebih dari satu
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label>Dokumen SOP</label>
                    <div class="file-box mt-1">
                        <input type="file" name="sop[]"
                               class="form-control"
                               accept=".pdf,.doc,.docx"
                               multiple>
                        <div class="hint mt-2">
                            PDF / DOC • Maks 4MB
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ===================== ACTION ===================== --}}
        <div class="d-flex justify-content-end gap-2">
            <button class="btn btn-primary rounded-pill px-5 shadow-sm">
                💾 Simpan Fasilitas
            </button>
        </div>

    </form>
</div>
@endsection
