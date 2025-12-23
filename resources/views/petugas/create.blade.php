@extends('layouts.admin.app')
@section('title', 'Tambah Petugas Fasilitas')

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
        box-shadow: 0 10px 25px rgba(0,0,0,.08);
        border: none;
    }

    .select-hint {
        font-size: 12px;
        color: #6b7280;
    }

    .preview-box {
        background: #f9fafb;
        border-radius: 12px;
        padding: 14px;
        border: 1px dashed #c7d2fe;
    }
</style>
@endpush

@section('content')
<div class="container-fluid fade-in" style="padding-top:35px;">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0">➕ Tambah Petugas Fasilitas</h3>
            <small class="text-muted">Menentukan penanggung jawab fasilitas desa</small>
        </div>

        <a href="{{ route('petugas.index') }}" class="btn btn-secondary rounded-pill px-4">
            ← Kembali
        </a>
    </div>

    {{-- FORM --}}
    <div class="card form-card p-4">

        <form action="{{ route('petugas.store') }}" method="POST">
            @csrf

            <div class="row g-4">

                {{-- PILIH FASILITAS --}}
                <div class="col-md-6">
                    <label>Fasilitas</label>
                    <select name="fasilitas_id" class="form-select" required id="fasilitasSelect">
                        <option value="">-- Pilih Fasilitas --</option>
                        @foreach ($fasilitas as $f)
                            <option value="{{ $f->fasilitas_id }}">
                                {{ $f->nama }} ({{ ucfirst($f->jenis) }})
                            </option>
                        @endforeach
                    </select>
                    <div class="select-hint mt-1">
                        Fasilitas yang akan ditugaskan petugas
                    </div>
                </div>

                {{-- PILIH WARGA --}}
                <div class="col-md-6">
                    <label>Petugas (Warga)</label>
                    <select name="petugas_warga_id" class="form-select" required id="wargaSelect">
                        <option value="">-- Pilih Warga --</option>
                        @foreach ($warga as $w)
                            <option value="{{ $w->warga_id }}">
                                {{ $w->nama }} — {{ $w->no_ktp }}
                            </option>
                        @endforeach
                    </select>
                    <div class="select-hint mt-1">
                        Warga yang bertanggung jawab
                    </div>
                </div>

                {{-- PERAN --}}
                <div class="col-md-6">
                    <label>Peran / Jabatan</label>
                    <input type="text"
                           name="peran"
                           class="form-control"
                           placeholder="Contoh: Penanggung Jawab, Koordinator, Pengelola"
                           required
                           id="peranInput">
                </div>

                {{-- PREVIEW --}}
                <div class="col-md-6">
                    <label>Preview Penugasan</label>
                    <div class="preview-box" id="previewBox">
                        <div class="text-muted">
                            Pilih fasilitas dan warga untuk melihat ringkasan
                        </div>
                    </div>
                </div>

            </div>

            {{-- ACTION --}}
            <div class="d-flex justify-content-end mt-4">
                <button class="btn btn-primary rounded-pill px-5">
                    💾 Simpan Petugas
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

    function updatePreview() {
        const fasilitasText = fasilitasSelect.options[fasilitasSelect.selectedIndex]?.text || '-';
        const wargaText     = wargaSelect.options[wargaSelect.selectedIndex]?.text || '-';
        const peranText     = peranInput.value || '-';

        previewBox.innerHTML = `
            <strong>📌 Ringkasan Penugasan</strong>
            <hr class="my-2">
            <div><strong>Fasilitas:</strong> ${fasilitasText}</div>
            <div><strong>Petugas:</strong> ${wargaText}</div>
            <div><strong>Peran:</strong> ${peranText}</div>
        `;
    }

    fasilitasSelect.addEventListener('change', updatePreview);
    wargaSelect.addEventListener('change', updatePreview);
    peranInput.addEventListener('input', updatePreview);
</script>
@endpush
