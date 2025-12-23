@extends('layouts.admin.app')
@section('title', 'Tambah Pembayaran Fasilitas')

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

    .file-box {
        border: 2px dashed #dbe3f0;
        border-radius: 14px;
        padding: 18px;
        background: #fafcff;
    }

    .preview-box img {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #ddd;
    }
</style>
@endpush

@section('content')
<div class="container-fluid fade-in" style="padding-top:35px; max-width:1100px;">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0">💳 Tambah Pembayaran</h3>
            <small class="text-muted">Input pembayaran peminjaman fasilitas</small>
        </div>

        <a href="{{ route('pembayaran.index') }}"
           class="btn btn-outline-secondary rounded-pill px-4">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('pembayaran.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        {{-- ================= DATA PEMBAYARAN ================= --}}
        <div class="section-card shadow-sm">
            <div class="section-title">📄 Data Pembayaran</div>

            <div class="row g-3">

                <div class="col-md-6">
                    <label>Peminjaman</label>
                    <select name="pinjam_id" class="form-select" required>
                        <option value="">-- Pilih Peminjaman --</option>
                        @foreach ($peminjaman as $p)
                            <option value="{{ $p->pinjam_id }}">
                                {{ $p->fasilitas->nama }} — {{ $p->warga->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal"
                           class="form-control"
                           value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="col-md-3">
                    <label>Metode</label>
                    <select name="metode" class="form-select" required>
                        <option value="">-- Metode --</option>
                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Jumlah Pembayaran</label>
                    <input type="text"
                           id="rupiah"
                           class="form-control"
                           placeholder="Rp 0">
                    <input type="hidden"
                           name="jumlah"
                           id="jumlah_real"
                           required>
                    <div class="hint mt-1">
                        Otomatis format Rupiah
                    </div>
                </div>

                <div class="col-md-6">
                    <label>Keterangan</label>
                    <input type="text"
                           name="keterangan"
                           class="form-control"
                           placeholder="Pembayaran DP / Lunas / dll">
                </div>

            </div>
        </div>

        {{-- ================= RESI ================= --}}
        <div class="section-card shadow-sm">
            <div class="section-title">📎 Upload Resi</div>

            <div class="file-box">
                <input type="file"
                       name="resi[]"
                       class="form-control"
                       accept="image/*,.pdf"
                       multiple
                       onchange="previewResi(this)">
                <div class="hint mt-2">
                    JPG / PNG / PDF • Maks 4MB • Bisa lebih dari satu
                </div>

                <div id="preview" class="preview-box d-flex flex-wrap gap-2 mt-3"></div>
            </div>
        </div>

        {{-- ACTION --}}
        <div class="d-flex justify-content-end">
            <button class="btn btn-primary rounded-pill px-5 shadow-sm">
                💾 Simpan Pembayaran
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

    // PREVIEW RESI
    function previewResi(input) {
        const preview = document.getElementById('preview');
        preview.innerHTML = '';

        [...input.files].forEach(file => {
            if (file.type.startsWith('image')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
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
