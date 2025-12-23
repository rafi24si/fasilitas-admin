@extends('layouts.admin.app')
@section('title', 'Pembayaran Fasilitas')

@push('styles')
<style>
    .fade-in { animation: fadeIn .35s ease-in-out; }
    @keyframes fadeIn {
        from { opacity:0; transform:translateY(10px); }
        to { opacity:1; transform:none; }
    }

    .pay-card {
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        padding: 16px;
        background: #fff;
        transition: .25s;
        height: 100%;
    }

    .pay-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0,0,0,.12);
    }

    .badge-metode {
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 20px;
        background: #e3f2fd;
        color: #0d6efd;
        font-weight: 600;
    }

    .resi-thumb {
        width: 60px;
        height: 45px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #ddd;
    }

    .search-box {
        max-width: 260px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid fade-in" style="padding-top:35px;">

    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h3 class="fw-bold text-primary mb-0">💳 Pembayaran Fasilitas</h3>
            <small class="text-muted">Daftar pembayaran peminjaman fasilitas</small>
        </div>

        <div class="d-flex gap-2">
            <input type="text" id="searchInput"
                   class="form-control search-box"
                   placeholder="🔍 Cari warga / fasilitas">

            <select id="filterMetode" class="form-select">
                <option value="all">Semua Metode</option>
                <option value="cash">Cash</option>
                <option value="transfer">Transfer</option>
                <option value="qris">QRIS</option>
            </select>

            <a href="{{ route('pembayaran.create') }}"
               class="btn btn-primary rounded-pill px-4">
                + Tambah
            </a>
        </div>
    </div>

    {{-- CARD GRID --}}
    <div class="row g-4" id="paymentContainer">

        @forelse ($pembayaran as $p)
        <div class="col-md-4 col-lg-3 payment-card"
             data-metode="{{ strtolower($p->metode) }}"
             data-search="{{ strtolower($p->peminjaman->warga->nama ?? '') }} {{ strtolower($p->peminjaman->fasilitas->nama ?? '') }}">

            <div class="pay-card">

                {{-- HEADER --}}
                <div class="d-flex justify-content-between mb-1">
                    <div class="fw-bold">
                        {{ $p->peminjaman->fasilitas->nama ?? '-' }}
                    </div>
                    <span class="badge-metode">
                        {{ strtoupper($p->metode) }}
                    </span>
                </div>

                <div class="small text-muted mb-2">
                    👤 {{ $p->peminjaman->warga->nama ?? '-' }}
                </div>

                <div class="small mb-1">
                    📅 {{ $p->tanggal->format('d M Y') }}
                </div>

                <div class="fw-bold text-success mb-2 amount"
                     data-amount="{{ $p->jumlah }}">
                    Rp {{ number_format($p->jumlah,0,',','.') }}
                </div>

                {{-- RESI --}}
                @if ($p->media->count())
                    <div class="mb-2">
                        <div class="small fw-semibold mb-1">Resi:</div>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($p->media as $m)
                                <a href="{{ asset('storage/'.$m->file_url) }}"
                                   target="_blank">
                                    @if (str_starts_with($m->mime_type, 'image'))
                                        <img src="{{ asset('storage/'.$m->file_url) }}"
                                             class="resi-thumb">
                                    @else
                                        <span class="badge bg-info text-white">
                                            📄 PDF
                                        </span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="small text-muted mb-2">
                        Tidak ada resi
                    </div>
                @endif

                {{-- ACTION --}}
                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('pembayaran.edit', $p) }}"
                       class="btn btn-sm btn-outline-warning">
                        ✏️ Edit
                    </a>

                    <form action="{{ route('pembayaran.destroy', $p) }}"
                          method="POST"
                          onsubmit="return confirm('Hapus pembayaran ini?')">
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
                Belum ada data pembayaran
            </div>
        @endforelse

    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $pembayaran->links('pagination::bootstrap-5') }}
    </div>

</div>

{{-- =========================
     JS INTERAKTIF
========================= --}}
<script>
    const searchInput = document.getElementById('searchInput');
    const filterMetode = document.getElementById('filterMetode');
    const cards = document.querySelectorAll('.payment-card');

    function filterCards() {
        const search = searchInput.value.toLowerCase();
        const metode = filterMetode.value;

        cards.forEach(card => {
            const text = card.dataset.search;
            const mtd  = card.dataset.metode;

            const matchSearch = text.includes(search);
            const matchMetode = metode === 'all' || metode === mtd;

            card.style.display = (matchSearch && matchMetode)
                ? 'block'
                : 'none';
        });
    }

    searchInput.addEventListener('input', filterCards);
    filterMetode.addEventListener('change', filterCards);
</script>
@endsection
