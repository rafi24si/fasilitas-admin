@extends('layouts.admin.app')
@section('title', 'Edit Pembayaran')

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

    .hint { font-size: 12px; color: #6c757d; }

    .media-box {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 10px;
        text-align: center;
        background: #fafafa;
        position: relative;
    }

    .media-box img {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid fade-in" style="padding-top:35px; max-width:1100px;">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0">✏️ Edit Pembayaran</h3>
            <small class="text-muted">Perbarui data pembayaran fasilitas</small>
        </div>

        <a href="{{ route('pembayaran.index') }}"
           class="btn btn-outline-secondary rounded-pill px-4">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('pembayaran.update', $pembayaran->bayar_id) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- ================= DATA PEMBAYARAN ================= --}}
        <div class="section-card shadow-sm">
            <div class="section-title">📄 Data Pembayaran</div>

            <div class="row g-3">

                <div class="col-md-6">
                    <label>Peminjaman</label>
                    <select name="pinjam_id" class="form-select" required>
                        @foreach ($peminjaman as $p)
                            <option value="{{ $p->pinjam_id }}"
                                {{ $pembayaran->pinjam_id == $p->pinjam_id ? 'selected' : '' }}>
                                {{ $p->fasilitas->nama }} — {{ $p->warga->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal"
                           class="form-control"
                           value="{{ $pembayaran->tanggal }}" required>
                </div>

                <div class="col-md-3">
                    <label>Metode</label>
                    <select name="metode" class="form-select" required>
                        @foreach (['cash','transfer','qris'] as $m)
                            <option value="{{ $m }}"
                                {{ $pembayaran->metode == $m ? 'selected' : '' }}>
                                {{ strtoupper($m) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Jumlah Pembayaran</label>
                    <input type="text"
                           id="rupiah"
                           class="form-control"
                           value="Rp {{ number_format($pembayaran->jumlah,0,',','.') }}">
                    <input type="hidden"
                           name="jumlah"
                           id="jumlah_real"
                           value="{{ $pembayaran->jumlah }}"
                           required>
                </div>

                <div class="col-md-6">
                    <label>Keterangan</label>
                    <input type="text"
                           name="keterangan"
                           class="form-control"
                           value="{{ $pembayaran->keterangan }}">
                </div>

            </div>
        </div>

        {{-- ================= RESI LAMA ================= --}}
        <div class="section-card shadow-sm">
            <div class="section-title">🗂️ Resi Tersimpan</div>

            <div class="row g-3">
                @forelse ($pembayaran->media as $m)
                    <div class="col-md-3">
                        <div class="media-box">

                            <div class="form-check mb-1">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="hapus_media[]"
                                       value="{{ $m->media_id }}">
                                <label class="form-check-label text-danger small">
                                    Hapus
                                </label>
                            </div>

                            @if (str_starts_with($m->mime_type, 'image'))
                                <img src="{{ asset('storage/'.$m->file_url) }}">
                            @else
                                <i class="ti ti-file-text fs-1 text-secondary"></i>
                                <div class="small">PDF</div>
                            @endif

                        </div>
                    </div>
                @empty
                    <div class="col-12 text-muted">Belum ada resi</div>
                @endforelse
            </div>
        </div>

        {{-- ================= RESI BARU ================= --}}
        <div class="section-card shadow-sm">
            <div class="section-title">➕ Tambah Resi Baru</div>

            <input type="file"
                   name="resi[]"
                   class="form-control"
                   accept="image/*,.pdf"
                   multiple
                   onchange="previewResi(this)">

            <div class="hint mt-2">
                JPG / PNG / PDF • Maks 4MB
            </div>

            <div id="preview" class="d-flex gap-2 flex-wrap mt-3"></div>
        </div>

        {{-- ACTION --}}
        <div class="d-flex justify-content-end">
            <button class="btn btn-primary rounded-pill px-5 shadow-sm">
                💾 Simpan Perubahan
            </button>
        </div>

    </form>
</div>

{{-- =========================
     JAVASCRIPT
========================= --}}
<script>
    // FORMAT RUPIAH
    const rupiah = document.getElementById('rupiah');
    const jumlahReal = document.getElementById('jumlah_real');

    rupiah.addEventListener('input', function () {
        let val = this.value.replace(/[^\d]/g, '');
        jumlahReal.value = val;
        this.value = val ? 'Rp ' + new Intl.NumberFormat('id-ID').format(val) : '';
    });

    // PREVIEW RESI BARU
    function previewResi(input) {
        const preview = document.getElementById('preview');
        preview.innerHTML = '';

        [...input.files].forEach(file => {
            if (file.type.startsWith('image')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.style.width = '80px';
                img.style.height = '60px';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '8px';
                preview.appendChild(img);
            } else {
                const badge = document.createElement('span');
                badge.className = 'badge bg-info text-white';
                badge.innerText = '📄 ' + file.name;
                preview.appendChild(badge);
            }
        });
    }
</script>
@endsection
