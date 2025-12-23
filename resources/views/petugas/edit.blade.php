@extends('layouts.admin.app')
@section('title', 'Edit Petugas Fasilitas')

@push('styles')
<style>
    .fade-in { animation: fadeIn .3s ease-in-out; }
    @keyframes fadeIn {
        from { opacity:0; transform:translateY(10px); }
        to { opacity:1; transform:none; }
    }

    label { font-weight:600; }

    .form-card {
        border-radius: 18px;
        box-shadow: 0 12px 30px rgba(0,0,0,.08);
        border: none;
    }

    .preview-box {
        background: linear-gradient(135deg, #f8fafc, #eef2ff);
        border-radius: 14px;
        padding: 16px;
        border: 1px dashed #c7d2fe;
    }

    .preview-box strong {
        color: #1e3a8a;
    }

    .hint {
        font-size: 12px;
        color: #6b7280;
    }
</style>
@endpush

@section('content')
<div class="container-fluid fade-in" style="padding-top:35px;">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0">✏️ Edit Petugas Fasilitas</h3>
            <small class="text-muted">Perbarui penugasan dan peran petugas</small>
        </div>

        <a href="{{ route('petugas.index') }}"
           class="btn btn-secondary rounded-pill px-4">
            ← Kembali
        </a>
    </div>

    {{-- FORM --}}
    <div class="card form-card p-4">

        <form action="{{ route('petugas.update', $petugas->petugas_id) }}"
              method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">

                {{-- FASILITAS --}}
                <div class="col-md-6">
                    <label>Fasilitas</label>
                    <select name="fasilitas_id"
                            class="form-select"
                            required
                            id="fasilitasSelect">
                        @foreach ($fasilitas as $f)
                            <option value="{{ $f->fasilitas_id }}"
                                {{ $petugas->fasilitas_id == $f->fasilitas_id ? 'selected' : '' }}>
                                {{ $f->nama }} ({{ ucfirst($f->jenis) }})
                            </option>
                        @endforeach
                    </select>
                    <div class="hint mt-1">
                        Fasilitas yang menjadi tanggung jawab
                    </div>
                </div>

                {{-- WARGA --}}
                <div class="col-md-6">
                    <label>Petugas (Warga)</label>
                    <select name="petugas_warga_id"
                            class="form-select"
                            required
                            id="wargaSelect">
                        @foreach ($warga as $w)
                            <option value="{{ $w->warga_id }}"
                                {{ $petugas->petugas_warga_id == $w->warga_id ? 'selected' : '' }}>
                                {{ $w->nama }} — {{ $w->no_ktp }}
                            </option>
                        @endforeach
                    </select>
                    <div class="hint mt-1">
                        Warga yang ditugaskan
                    </div>
                </div>

                {{-- PERAN --}}
                <div class="col-md-6">
                    <label>Peran / Jabatan</label>
                    <input type="text"
                           name="peran"
                           id="peranInput"
                           class="form-control"
                           value="{{ old('peran', $petugas->peran) }}"
                           required>
                </div>

                {{-- PREVIEW --}}
                <div class="col-md-6">
                    <label>Preview Penugasan</label>
                    <div class="preview-box" id="previewBox"></div>
                </div>

            </div>

            {{-- ACTION --}}
            <div class="d-flex justify-content-end mt-4 gap-2">
                <button class="btn btn-primary rounded-pill px-5">
                    💾 Simpan Perubahan
                </button>
            </div>

        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
    const fasilitasSelect = document.getElementById('fasilitasSelect');
    const wargaSelect     = document.getElementById('wargaSelect');
    const peranInput      = document.getElementById('peranInput');
    const previewBox      = document.getElementById('previewBox');

    function renderPreview() {
        const fasilitasText = fasilitasSelect.options[fasilitasSelect.selectedIndex]?.text || '-';
        const wargaText     = wargaSelect.options[wargaSelect.selectedIndex]?.text || '-';
        const peranText     = peranInput.value || '-';

        previewBox.innerHTML = `
            <strong>📌 Ringkasan Petugas</strong>
            <hr class="my-2">
            <div><strong>Fasilitas:</strong> ${fasilitasText}</div>
            <div><strong>Petugas:</strong> ${wargaText}</div>
            <div><strong>Peran:</strong> ${peranText}</div>
        `;
    }

    // initial render
    renderPreview();

    fasilitasSelect.addEventListener('change', renderPreview);
    wargaSelect.addEventListener('change', renderPreview);
    peranInput.addEventListener('input', renderPreview);
</script>
@endpush
