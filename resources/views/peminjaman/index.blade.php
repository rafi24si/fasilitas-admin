@extends('layouts.admin.app')
@section('title', 'Peminjaman Fasilitas')

@push('styles')
    <style>
        .fade-in {
            animation: fadeIn .3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .p-card {
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            transition: .25s;
            background: #fff;
            height: 100%;
        }

        .p-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, .12);
        }

        .status {
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
        }

        .status-menunggu {
            background: #fff3cd;
            color: #856404;
        }

        .status-disetujui {
            background: #e6f4ea;
            color: #0f5132;
        }

        .status-ditolak {
            background: #fdecea;
            color: #842029;
        }

        .status-selesai {
            background: #e7f1ff;
            color: #084298;
        }

        .status-batal {
            background: #f8d7da;
            color: #842029;
        }

        .bukti-thumb {
            width: 60px;
            height: 45px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid fade-in" style="padding-top:35px;">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-primary mb-0">📅 Peminjaman Fasilitas</h3>
                <small class="text-muted">Daftar peminjaman fasilitas desa</small>
            </div>

            <a href="{{ route('peminjaman.create') }}" class="btn btn-primary rounded-pill px-4">
                + Tambah Peminjaman
            </a>
        </div>


        <form method="GET" class="row g-2 mb-4">

            <div class="col-md-4">
                <input type="text" name="q" class="form-control" placeholder="Cari warga / fasilitas / tujuan"
                    value="{{ request('q') }}">
            </div>

            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div class="col-md-2">
                <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
            </div>

            <div class="col-md-2">
                <input type="date" name="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
            </div>

            <div class="col-md-1">
                <select name="sort" class="form-select">
                    <option value="latest">Terbaru</option>
                    <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                    <option value="tanggal" {{ request('sort') == 'tanggal' ? 'selected' : '' }}>Tanggal</option>
                </select>
            </div>

            <div class="col-md-1">
                <button class="btn btn-primary w-100">
                    🔍
                </button>
            </div>

        </form>

        {{-- CARD GRID --}}
        <div class="row g-4">

            @forelse ($pinjam as $p)
                <div class="col-md-4 col-lg-3">
                    <div class="p-card p-3">

                        {{-- HEADER --}}
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="fw-bold">
                                {{ $p->fasilitas->nama ?? '-' }}
                            </div>

                            <span class="status status-{{ $p->status }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </div>

                        <div class="small text-muted mb-2">
                            👤 {{ $p->warga->nama ?? '-' }}
                        </div>

                        <div class="small mb-2">
                            📆 {{ $p->tanggal_mulai->format('d M Y') }}
                            –
                            {{ $p->tanggal_selesai->format('d M Y') }}
                        </div>

                        <div class="small mb-2">
                            🎯 {{ $p->tujuan ?? 'Tidak ada tujuan' }}
                        </div>

                        <div class="small mb-2">
                            💰 <strong>Rp {{ number_format($p->total_biaya, 0, ',', '.') }}</strong>
                        </div>

                        {{-- BUKTI BAYAR --}}
                        @if ($p->media->count())
                            <div class="mb-2">
                                <div class="fw-semibold small mb-1">Bukti Bayar:</div>

                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($p->media as $m)
                                        <a href="{{ asset('storage/' . $m->file_url) }}" target="_blank"
                                            title="Lihat bukti bayar">
                                            @if (str_starts_with($m->mime_type, 'image'))
                                                <img src="{{ asset('storage/' . $m->file_url) }}" class="bukti-thumb">
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
                                Bukti bayar belum ada
                            </div>
                        @endif

                        {{-- ACTION --}}
                        <div class="d-flex justify-content-between mt-3">

                            <a href="{{ route('peminjaman.edit', $p) }}" class="btn btn-sm btn-outline-warning">
                                ✏️ Edit
                            </a>

                            <form action="{{ route('peminjaman.destroy', $p) }}" method="POST"
                                onsubmit="return confirm('Hapus data peminjaman ini?')">
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
                    Belum ada data peminjaman
                </div>
            @endforelse

        </div>

        {{-- PAGINATION --}}
        <div class="mt-4">
            {{ $pinjam->links('pagination::bootstrap-5') }}
        </div>

    </div>
@endsection
