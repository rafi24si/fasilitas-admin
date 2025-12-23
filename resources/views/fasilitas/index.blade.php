@extends('layouts.admin.app')
@section('title', 'Data Fasilitas Umum')

@push('styles')
    <style>
        /* =====================
           ANIMATION
        ===================== */
        .fade-in {
            animation: fadeIn .35s ease-in-out;
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

        /* =====================
           CARD
        ===================== */
        .f-card {
            border-radius: 18px;
            overflow: hidden;
            transition: .3s ease;
            border: 1px solid #e5e7eb;
            background: #ffffff;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .08);
        }

        .f-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 14px 34px rgba(0, 0, 0, .16);
        }

        /* =====================
           IMAGE
        ===================== */
        .f-img {
            height: 170px;
            width: 100%;
            object-fit: cover;
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            color: #15803d;
            font-weight: 600;
        }

        /* =====================
           BADGE JENIS
        ===================== */
        .badge-jenis {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            color: #ffffff;
            font-size: 11px;
            padding: 5px 14px;
            border-radius: 999px;
            font-weight: 600;
            letter-spacing: .3px;
            box-shadow: 0 4px 12px rgba(34, 197, 94, .35);
        }

        /* =====================
           SOP BUTTON
        ===================== */
        .sop-btn {
            font-size: 12px;
            padding: 6px 14px;
            border-radius: 999px;
            font-weight: 600;
            border: 1px solid #22c55e;
            color: #15803d;
            background: #ecfdf5;
            transition: .25s ease;
        }

        .sop-btn:hover {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            color: #ffffff;
            box-shadow: 0 8px 22px rgba(34, 197, 94, .45);
        }
    </style>
@endpush


@section('content')
    <div class="container-fluid fade-in" style="padding-top:35px;">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-primary mb-0">🏢 Fasilitas Umum</h3>
                <small class="text-muted">Daftar fasilitas desa</small>
            </div>

            <a href="{{ route('fasilitas.create') }}" class="btn btn-primary px-4 rounded-pill">
                + Tambah Fasilitas
            </a>
        </div>
        <form method="GET" class="row g-2 mb-4">

            <div class="col-md-4">
                <input type="text" name="q" class="form-control" placeholder="Cari fasilitas..."
                    value="{{ request('q') }}">
            </div>

            <div class="col-md-3">
                <select name="jenis" class="form-select">
                    <option value="">Semua Jenis</option>
                    <option value="aula" {{ request('jenis') == 'aula' ? 'selected' : '' }}>Aula</option>
                    <option value="lapangan" {{ request('jenis') == 'lapangan' ? 'selected' : '' }}>Lapangan</option>
                    <option value="balai" {{ request('jenis') == 'balai' ? 'selected' : '' }}>Balai Desa</option>
                </select>
            </div>

            <div class="col-md-2">
                <input type="number" name="kapasitas_min" class="form-control" placeholder="Min Kapasitas"
                    value="{{ request('kapasitas_min') }}">
            </div>

            <div class="col-md-2">
                <select name="sort" class="form-select">
                    <option value="latest">Terbaru</option>
                    <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                    <option value="nama" {{ request('sort') == 'nama' ? 'selected' : '' }}>Nama A–Z</option>
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

            @forelse ($fasilitas as $f)
                <div class="col-md-4 col-lg-3">

                    <div class="card f-card h-100">

                        {{-- FOTO --}}
                        @if ($f->fotoUtama)
                            <img src="{{ asset('storage/' . $f->fotoUtama->file_url) }}" class="f-img">
                        @else
                            <div class="d-flex align-items-center justify-content-center f-img text-muted">
                                Tidak ada foto
                            </div>
                        @endif

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold mb-0">{{ $f->nama }}</h6>
                                <span class="badge-jenis">{{ ucfirst($f->jenis) }}</span>
                            </div>

                            <small class="text-muted d-block mb-2">
                                📍 {{ $f->alamat ?? 'Alamat belum diisi' }}
                            </small>

                            <div class="small mb-2">
                                RT {{ $f->rt ?? '-' }} / RW {{ $f->rw ?? '-' }} <br>
                                Kapasitas:
                                <strong>{{ $f->kapasitas ? $f->kapasitas . ' orang' : '-' }}</strong>
                            </div>

                            {{-- SOP --}}
                            @if ($f->sopFiles->count())
                                <div class="mb-2">
                                    @foreach ($f->sopFiles as $sop)
                                        <a href="{{ asset('storage/' . $sop->file_url) }}" target="_blank"
                                            class="btn btn-outline-info btn-sm sop-btn mb-1">
                                            📄 SOP
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="small text-muted mb-2">
                                    SOP belum tersedia
                                </div>
                            @endif

                        </div>

                        {{-- FOOTER --}}
                        <div class="card-footer bg-white border-0 d-flex justify-content-between">

                            <a href="{{ route('fasilitas.edit', $f) }}" class="btn btn-sm btn-outline-warning">
                                ✏️ Edit
                            </a>

                            <form action="{{ route('fasilitas.destroy', $f) }}" method="POST"
                                onsubmit="return confirm('Hapus fasilitas ini?')">
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
                    Belum ada data fasilitas
                </div>
            @endforelse

        </div>

        {{-- PAGINATION --}}
        <div class="mt-4">
            {{ $fasilitas->links('pagination::bootstrap-5') }}
        </div>

    </div>
@endsection
