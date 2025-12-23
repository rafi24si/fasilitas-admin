@extends('layouts.admin.app')
@section('title', 'Petugas Fasilitas')

@push('styles')
<style>
    .fade-in { animation: fadeIn .35s ease-in-out; }
    @keyframes fadeIn {
        from { opacity:0; transform:translateY(10px); }
        to { opacity:1; transform:none; }
    }

    .petugas-card {
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        background: #fff;
        transition: .25s;
    }

    .petugas-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,.12);
    }

    .badge-fasilitas {
        background: #e3f2fd;
        color: #0d6efd;
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 20px;
    }

    .badge-peran {
        background: #f1f5f9;
        color: #334155;
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 20px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid fade-in" style="padding-top:35px;">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0">👷 Petugas Fasilitas</h3>
            <small class="text-muted">Penanggung jawab fasilitas desa</small>
        </div>

        <a href="{{ route('petugas.create') }}"
           class="btn btn-primary rounded-pill px-4">
            + Tambah Petugas
        </a>
    </div>

    {{-- FILTER & SEARCH --}}
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <input type="text"
                   id="searchInput"
                   class="form-control"
                   placeholder="🔍 Cari nama petugas...">
        </div>

        <div class="col-md-4">
            <select id="filterFasilitas" class="form-select">
                <option value="all">Semua Fasilitas</option>
                @foreach ($petugas->pluck('fasilitas')->unique('fasilitas_id') as $f)
                    <option value="{{ $f->nama }}">{{ $f->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- CARD GRID --}}
    <div class="row g-4" id="petugasContainer">

        @forelse ($petugas as $p)
            <div class="col-md-4 petugas-item"
                 data-nama="{{ strtolower($p->warga->nama) }}"
                 data-fasilitas="{{ $p->fasilitas->nama }}">

                <div class="petugas-card p-4 h-100">

                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="fw-bold mb-0">{{ $p->warga->nama }}</h6>
                        <span class="badge-fasilitas">
                            {{ $p->fasilitas->nama }}
                        </span>
                    </div>

                    <div class="small text-muted mb-2">
                        NIK: {{ $p->warga->no_ktp ?? '-' }}
                    </div>

                    <span class="badge-peran mb-2 d-inline-block">
                        {{ $p->peran }}
                    </span>

                    {{-- ACTION --}}
                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('petugas.edit', $p->petugas_id) }}"
                           class="btn btn-sm btn-outline-warning">
                            ✏️ Edit
                        </a>

                        <form action="{{ route('petugas.destroy', $p->petugas_id) }}"
                              method="POST"
                              onsubmit="return confirm('Hapus petugas ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                🗑️ Hapus
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                Belum ada data petugas fasilitas
            </div>
        @endforelse

    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $petugas->links('pagination::bootstrap-5') }}
    </div>

</div>

{{-- =========================
     JAVASCRIPT
========================= --}}
<script>
    const searchInput = document.getElementById('searchInput');
    const filterFasilitas = document.getElementById('filterFasilitas');
    const items = document.querySelectorAll('.petugas-item');

    function filterPetugas() {
        const search = searchInput.value.toLowerCase();
        const fasilitas = filterFasilitas.value;

        items.forEach(item => {
            const nama = item.dataset.nama;
            const fas  = item.dataset.fasilitas;

            const matchNama = nama.includes(search);
            const matchFas  = fasilitas === 'all' || fas === fasilitas;

            item.style.display = (matchNama && matchFas) ? 'block' : 'none';
        });
    }

    searchInput.addEventListener('input', filterPetugas);
    filterFasilitas.addEventListener('change', filterPetugas);
</script>
@endsection
