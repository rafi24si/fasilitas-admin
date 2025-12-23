@extends('layouts.admin.app')
@section('title', 'Edit Peminjaman Fasilitas')

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

    .media-box {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 10px;
        text-align: center;
    }

    .media-thumb {
        width: 100%;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid fade-in" style="padding-top:35px; max-width:1200px;">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0">✏️ Edit Peminjaman Fasilitas</h3>
            <small class="text-muted">Perbarui data peminjaman fasilitas</small>
        </div>

        <a href="{{ route('peminjaman.index') }}"
           class="btn btn-outline-secondary rounded-pill px-4">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('peminjaman.update', $peminjaman) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- ================= DATA PEMINJAMAN ================= --}}
        <div class="section-card shadow-sm">

            <div class="section-title">
                🏢 Data Peminjaman
            </div>

            <div class="row g-3">

                {{-- FASILITAS --}}
                <div class="col-md-6">
                    <label>Fasilitas</label>
                    <select name="fasilitas_id" class="form-select" required>
                        @foreach ($fasilitas as $f)
                            <option value="{{ $f->fasilitas_id }}"
                                {{ $peminjaman->fasilitas_id == $f->fasilitas_id ? 'selected' : '' }}>
                                {{ $f->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- WARGA --}}
                <div class="col-md-6">
                    <label>Warga Peminjam</label>
                    <select name="warga_id" class="form-select" required>
                        @foreach ($warga as $w)
                            <option value="{{ $w->warga_id }}"
                                {{ $peminjaman->warga_id == $w->warga_id ? 'selected' : '' }}>
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
                           value="{{ $peminjaman->tanggal_mulai->format('Y-m-d') }}"
                           required>
                </div>

                <div class="col-md-4">
                    <label>Tanggal Selesai</label>
                    <input type="date"
                           name="tanggal_selesai"
                           class="form-control"
                           value="{{ $peminjaman->tanggal_selesai->format('Y-m-d') }}"
                           required>
                </div>

                {{-- BIAYA --}}
                <div class="col-md-4">
                    <label>Total Biaya (Rp)</label>
                    <input type="number"
                           name="total_biaya"
                           class="form-control"
                           min="0"
                           value="{{ $peminjaman->total_biaya }}">
                </div>

                {{-- TUJUAN --}}
                <div class="col-md-12">
                    <label>Tujuan Peminjaman</label>
                    <input type="text"
                           name="tujuan"
                           class="form-control"
                           value="{{ $peminjaman->tujuan }}">
                </div>

                {{-- STATUS --}}
                <div class="col-md-4">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        @foreach (['menunggu','disetujui','ditolak','selesai','batal'] as $st)
                            <option value="{{ $st }}"
                                {{ $peminjaman->status === $st ? 'selected' : '' }}>
                                {{ ucfirst($st) }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

        {{-- ================= BUKTI BAYAR LAMA ================= --}}
        <div class="section-card shadow-sm">

            <div class="section-title">
                📎 Bukti Bayar Tersimpan
            </div>

            <div class="row g-3">
                @forelse ($peminjaman->media as $m)
                    <div class="col-md-3">
                        <div class="media-box">

                            <div class="form-check mb-1 text-start">
                                <input type="checkbox"
                                       class="form-check-input"
                                       name="hapus_media[]"
                                       value="{{ $m->media_id }}">
                                <label class="form-check-label small text-danger">
                                    Hapus
                                </label>
                            </div>

                            @if (str_starts_with($m->mime_type, 'image'))
                                <img src="{{ asset('storage/'.$m->file_url) }}"
                                     class="media-thumb">
                            @else
                                <a href="{{ asset('storage/'.$m->file_url) }}"
                                   target="_blank"
                                   class="btn btn-outline-info btn-sm mt-2">
                                    📄 Lihat PDF
                                </a>
                            @endif

                        </div>
                    </div>
                @empty
                    <div class="text-muted">Belum ada bukti bayar</div>
                @endforelse
            </div>
        </div>

        {{-- ================= TAMBAH BUKTI BARU ================= --}}
        <div class="section-card shadow-sm">

            <div class="section-title">
                ➕ Tambah Bukti Bayar
            </div>

            <input type="file"
                   name="bukti_bayar[]"
                   class="form-control"
                   accept="image/*,.pdf"
                   multiple>

            <div class="hint mt-2">
                JPG / PNG / PDF • Maks 4MB
            </div>

        </div>

        {{-- ================= ACTION ================= --}}
        <div class="d-flex justify-content-end">
            <button class="btn btn-primary rounded-pill px-5 shadow-sm">
                💾 Simpan Perubahan
            </button>
        </div>

    </form>
</div>
@endsection
