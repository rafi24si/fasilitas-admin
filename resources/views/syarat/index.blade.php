@extends('layouts.admin.app')
@section('title', 'Syarat Fasilitas')

@push('styles')
<style>
    .fade-in { animation: fadeIn .35s ease-in-out; }
    @keyframes fadeIn {
        from { opacity:0; transform:translateY(10px); }
        to { opacity:1; transform:none; }
    }

    .syarat-card {
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        transition: .25s;
        background: #fff;
    }

    .syarat-card:hover {
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

    .doc-btn {
        font-size: 12px;
        padding: 4px 10px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid fade-in" style="padding-top:35px;">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0">📑 Syarat Fasilitas</h3>
            <small class="text-muted">Ketentuan & dokumen pendukung fasilitas</small>
        </div>

        <a href="{{ route('syarat.create') }}"
           class="btn btn-primary rounded-pill px-4">
            + Tambah Syarat
        </a>
    </div>

    {{-- FILTER & SEARCH --}}
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <input type="text"
                   id="searchInput"
                   class="form-control"
                   placeholder="🔍 Cari nama syarat...">
        </div>

        <div class="col-md-4">
            <select id="filterFasilitas" class="form-select">
                <option value="all">Semua Fasilitas</option>
                @foreach ($syarat->pluck('fasilitas')->unique('fasilitas_id') as $f)
                    <option value="{{ $f->nama }}">{{ $f->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- CARD GRID --}}
    <div class="row g-4" id="syaratContainer">

        @forelse ($syarat as $s)
            <div class="col-md-4 syarat-item"
                 data-nama="{{ strtolower($s->nama_syarat) }}"
                 data-fasilitas="{{ $s->fasilitas->nama }}">

                <div class="syarat-card p-4 h-100">

                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="fw-bold mb-0">{{ $s->nama_syarat }}</h6>
                        <span class="badge-fasilitas">
                            {{ $s->fasilitas->nama }}
                        </span>
                    </div>

                    <p class="small text-muted mb-2">
                        {{ $s->deskripsi ?? 'Tidak ada deskripsi' }}
                    </p>

                    {{-- DOKUMEN --}}
                    @if ($s->media->count())
                        <div class="mb-2">
                            @foreach ($s->media as $m)
                                <a href="{{ asset('storage/'.$m->file_url) }}"
                                   target="_blank"
                                   class="btn btn-outline-info btn-sm doc-btn mb-1">
                                    📄 Dokumen
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="small text-muted mb-2">
                            Dokumen belum tersedia
                        </div>
                    @endif

                    {{-- ACTION --}}
                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('syarat.edit', $s->syarat_id) }}"
                           class="btn btn-sm btn-outline-warning">
                            ✏️ Edit
                        </a>

                        <form action="{{ route('syarat.destroy', $s->syarat_id) }}"
                              method="POST"
                              onsubmit="return confirm('Hapus syarat ini?')">
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
            <div class="col-12 text-center text-muted py-5">
                Belum ada data syarat fasilitas
            </div>
        @endforelse

    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $syarat->links('pagination::bootstrap-5') }}
    </div>

</div>

{{-- =========================
     JAVASCRIPT
========================= --}}
<script>
    const searchInput = document.getElementById('searchInput');
    const filterFasilitas = document.getElementById('filterFasilitas');
    const items = document.querySelectorAll('.syarat-item');

    function filterData() {
        const search = searchInput.value.toLowerCase();
        const fasilitas = filterFasilitas.value;

        items.forEach(item => {
            const nama = item.dataset.nama;
            const fas = item.dataset.fasilitas;

            const matchSearch = nama.includes(search);
            const matchFas = fasilitas === 'all' || fas === fasilitas;

            item.style.display = matchSearch && matchFas ? 'block' : 'none';
        });
    }

    searchInput.addEventListener('input', filterData);
    filterFasilitas.addEventListener('change', filterData);
</script>
@endsection
