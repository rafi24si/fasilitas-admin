@extends('layouts.admin.app')
@section('title', 'Tambah Peminjaman Fasilitas')

@push('styles')
<style>
    .fade-in { animation: fadeIn .35s ease-in-out; }
    @keyframes fadeIn {
        from { opacity:0; transform:translateY(12px); }
        to { opacity:1; transform:none; }
    }

    label { font-weight:600; }

    .section-card {
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        padding: 20px;
        background: #fff;
        margin-bottom: 22px;
    }

    .section-title {
        font-weight: 700;
        color: #0d6efd;
        margin-bottom: 14px;
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
            <h3 class="fw-bold text-primary mb-0">📅 Tambah Peminjaman Fasilitas</h3>
            <small class="text-muted">Input data peminjaman fasilitas desa</small>
        </div>

        <a href="{{ route('peminjaman.index') }}"
           class="btn btn-outline-secondary rounded-pill px-4">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('peminjaman.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        {{-- ================= DATA PEMINJAMAN ================= --}}
        <div class="section-card shadow-sm">

            <div class="section-title">
                🏢 Data Peminjaman
            </div>

            <div class="row g-3">

                {{-- FASILITAS --}}
                <div class="col-md-6">
                    <label>Fasilitas</label>
                    <select name="fasilitas_id"
                            class="form-select"
                            required>
                        <option value="">-- Pilih Fasilitas --</option>
                        @foreach ($fasilitas as $f)
                            <option value="{{ $f->fasilitas_id }}"
                                {{ old('fasilitas_id') == $f->fasilitas_id ? 'selected' : '' }}>
                                {{ $f->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- WARGA --}}
                <div class="col-md-6">
                    <label>Warga Peminjam</label>
                    <select name="warga_id"
                            class="form-select"
                            required>
                        <option value="">-- Pilih Warga --</option>
                        @foreach ($warga as $w)
                            <option value="{{ $w->warga_id }}"
                                {{ old('warga_id') == $w->warga_id ? 'selected' : '' }}>
                                {{ $w->nama }} ({{ $w->no_ktp }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- TANGGAL --}}
                <div class="col-md-4">
                    <label>Tanggal Mulai</label>
                    <input type="date"
                           name="tanggal_mulai"
                           class="form-control"
                           value="{{ old('tanggal_mulai') }}"
                           required>
                </div>

                <div class="col-md-4">
                    <label>Tanggal Selesai</label>
                    <input type="date"
                           name="tanggal_selesai"
                           class="form-control"
                           value="{{ old('tanggal_selesai') }}"
                           required>
                </div>

                {{-- BIAYA --}}
                <div class="col-md-4">
                    <label>Total Biaya (Rp)</label>
                    <input type="number"
                           name="total_biaya"
                           class="form-control"
                           min="0"
                           placeholder="0"
                           value="{{ old('total_biaya', 0) }}">
                </div>

                {{-- TUJUAN --}}
                <div class="col-md-12">
                    <label>Tujuan Peminjaman</label>
                    <input type="text"
                           name="tujuan"
                           class="form-control"
                           placeholder="Contoh: Rapat warga / Pernikahan"
                           value="{{ old('tujuan') }}">
                </div>

                {{-- STATUS --}}
                <div class="col-md-4">
                    <label>Status</label>
                    <select name="status"
                            class="form-select">
                        <option value="menunggu" selected>Menunggu</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                        <option value="selesai">Selesai</option>
                        <option value="batal">Batal</option>
                    </select>
                    <div class="hint mt-1">
                        Default: <strong>Menunggu</strong>
                    </div>
                </div>

            </div>
        </div>

        {{-- ================= BUKTI BAYAR ================= --}}
        <div class="section-card shadow-sm">

            <div class="section-title">
                💳 Bukti Pembayaran
            </div>

            <div class="row g-3">

                <div class="col-md-6">
                    <label>Upload Bukti Bayar</label>
                    <div class="file-box mt-1">
                        <input type="file"
                               name="bukti_bayar[]"
                               class="form-control"
                               accept="image/*,.pdf"
                               multiple>
                        <div class="hint mt-2">
                            JPG / PNG / PDF • Maks 4MB • Bisa lebih dari satu
                        </div>
                    </div>
                </div>

            </div>

        </div>

        {{-- ================= ACTION ================= --}}
        <div class="d-flex justify-content-end">
            <button class="btn btn-primary rounded-pill px-5 shadow-sm">
                💾 Simpan Peminjaman
            </button>
        </div>

    </form>
</div>
@endsection
