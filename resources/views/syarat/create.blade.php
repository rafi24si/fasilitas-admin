@extends('layouts.admin.app')
@section('title', 'Tambah Syarat Fasilitas')

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
        padding: 22px;
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

    .preview-box {
        border: 1px dashed #cfd4da;
        border-radius: 12px;
        padding: 10px;
        background: #fafafa;
        min-height: 70px;
    }

    .doc-badge {
        background: #e3f2fd;
        color: #0d6efd;
        border-radius: 10px;
        padding: 6px 10px;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid fade-in" style="padding-top:35px; max-width:1100px;">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0">➕ Tambah Syarat Fasilitas</h3>
            <small class="text-muted">Kelola ketentuan & dokumen fasilitas</small>
        </div>

        <a href="{{ route('syarat.index') }}"
           class="btn btn-outline-secondary rounded-pill px-4">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('syarat.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        {{-- ================= DATA UTAMA ================= --}}
        <div class="section-card shadow-sm">
            <div class="section-title">📄 Informasi Syarat</div>

            <div class="row g-3">

                <div class="col-md-6">
                    <label>Fasilitas</label>
                    <select name="fasilitas_id"
                            class="form-select"
                            required>
                        <option value="">-- Pilih Fasilitas --</option>
                        @foreach ($fasilitas as $f)
                            <option value="{{ $f->fasilitas_id }}">
                                {{ $f->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Nama Syarat</label>
                    <input type="text"
                           name="nama_syarat"
                           class="form-control"
                           placeholder="Contoh: Fotokopi KTP"
                           required>
                </div>

                <div class="col-md-12">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi"
                              class="form-control"
                              rows="3"
                              placeholder="Keterangan tambahan (opsional)"></textarea>
                </div>

            </div>
        </div>

        {{-- ================= DOKUMEN ================= --}}
        <div class="section-card shadow-sm">
            <div class="section-title">📎 Dokumen Pendukung</div>

            <input type="file"
                   name="dokumen[]"
                   class="form-control"
                   accept=".pdf,.doc,.docx"
                   multiple
                   onchange="previewDokumen(this)">

            <div class="hint mt-2">
                PDF / DOC / DOCX • Maks 4MB • Bisa lebih dari satu
            </div>

            <div id="preview"
                 class="preview-box d-flex gap-2 flex-wrap mt-3">
                <span class="text-muted small">
                    Preview dokumen akan muncul di sini
                </span>
            </div>
        </div>

        {{-- ACTION --}}
        <div class="d-flex justify-content-end">
            <button class="btn btn-primary rounded-pill px-5 shadow-sm">
                💾 Simpan Syarat
            </button>
        </div>

    </form>
</div>

{{-- =========================
     JAVASCRIPT
========================= --}}
<script>
    function previewDokumen(input) {
        const preview = document.getElementById('preview');
        preview.innerHTML = '';

        [...input.files].forEach(file => {
            const badge = document.createElement('span');
            badge.className = 'doc-badge';
            badge.innerHTML = '📄 ' + file.name;
            preview.appendChild(badge);
        });
    }
</script>
@endsection
