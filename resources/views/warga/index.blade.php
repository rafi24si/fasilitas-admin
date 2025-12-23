@extends('layouts.admin.app')
@section('title', 'Data Warga')

@push('styles')
    <style>
        .fade-in {
            animation: fadeIn .35s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        /* =====================
               CARD ROW
            ===================== */
        .warga-row {
            background: #ffffff;
            border-radius: 14px;
            padding: 16px 18px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .08);
            transition: .25s ease;
            border-left: 6px solid #16a34a;
        }

        .warga-row:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 32px rgba(0, 0, 0, .15);
        }

        /* =====================
               AVATAR
            ===================== */
        .avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, #16a34a, #22c55e);
        }

        /* =====================
               FILTER BOX
            ===================== */
        .filter-box {
            background: #ffffff;
            border-radius: 16px;
            padding: 18px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .08);
            border-top: 5px solid #16a34a;
        }

        /* =====================
               BUTTONS
            ===================== */
        .btn-green {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            color: #fff;
            border: none;
        }

        .btn-green:hover {
            background: linear-gradient(135deg, #15803d, #16a34a);
            color: #fff;
        }

        /* =====================
               SEARCH HIGHLIGHT
            ===================== */
        mark {
            background: #bbf7d0;
            padding: 0 3px;
            border-radius: 4px;
        }
    </style>
@endpush


@section('content')
    <div class="container-fluid fade-in" style="padding-top:35px;">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-primary mb-0">👥 Data Warga</h3>
                <small class="text-muted">Manajemen data penduduk desa</small>
            </div>

            <a href="{{ route('warga.create') }}" class="btn btn-primary rounded-pill px-4">
                + Tambah Warga
            </a>
        </div>

        {{-- FILTER --}}
        <div class="filter-box mb-4">
            <form method="GET" class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="fw-semibold">Cari Data</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama / No KTP / Email"
                        value="{{ request('search') }}">
                </div>

                <div class="col-md-2">
                    <label class="fw-semibold">Gender</label>
                    <select name="jenis_kelamin" class="form-select">
                        <option value="all">Semua</option>
                        <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="fw-semibold">Agama</label>
                    <select name="agama" class="form-select">
                        <option value="all">Semua</option>
                        @foreach (['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $a)
                            <option value="{{ $a }}" {{ request('agama') == $a ? 'selected' : '' }}>
                                {{ $a }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="fw-semibold">Urutkan</label>
                    <select name="sort_by" class="form-select">
                        <option value="created_at">Terbaru</option>
                        <option value="nama" {{ request('sort_by') == 'nama' ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="no_ktp" {{ request('sort_by') == 'no_ktp' ? 'selected' : '' }}>No KTP</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-info w-100 rounded-pill">
                        Terapkan
                    </button>
                </div>

            </form>
        </div>

        {{-- LIST WARGA --}}
        <div class="d-flex flex-column gap-3">

            @forelse ($warga as $w)
                <div class="warga-row d-flex justify-content-between align-items-center">

                    {{-- LEFT --}}
                    <div class="d-flex align-items-center gap-3">

                        <div class="avatar {{ $w->jenis_kelamin == 'L' ? 'male' : 'female' }}">
                            {{ strtoupper(substr($w->nama, 0, 1)) }}
                        </div>

                        <div>
                            <div class="fw-bold">{{ $w->nama }}</div>
                            <small class="text-muted">
                                {{ $w->no_ktp }} • {{ $w->agama ?? '-' }}
                            </small>
                        </div>

                    </div>

                    {{-- CENTER --}}
                    <div class="d-none d-md-block">
                        <small class="text-muted d-block">Pekerjaan</small>
                        <strong>{{ $w->pekerjaan ?? '-' }}</strong>
                    </div>

                    {{-- RIGHT --}}
                    <div class="d-flex gap-2">

                        <a href="{{ route('warga.edit', $w->warga_id) }}"
                            class="btn btn-outline-warning btn-sm rounded-circle" title="Edit">
                            ✏️
                        </a>

                        <form action="{{ route('warga.destroy', $w->warga_id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus warga?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm rounded-circle" title="Hapus">
                                🗑️
                            </button>
                        </form>

                    </div>

                </div>
            @empty

                <div class="text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076500.png" width="90" class="mb-3">
                    <h5 class="text-muted">Belum ada data warga</h5>
                </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        <div class="mt-4">
            {{ $warga->links('pagination::bootstrap-5') }}
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        /* =========================
       AUTO SUBMIT FILTER
    ========================= */
        document.querySelectorAll('.filter-box select').forEach(el => {
            el.addEventListener('change', () => el.form.submit());
        });

        /* =========================
           CONFIRM DELETE MODERN
        ========================= */
        document.querySelectorAll('form[onsubmit]').forEach(form => {
            form.addEventListener('submit', e => {
                e.preventDefault();
                if (confirm('⚠️ Data warga akan dihapus permanen. Lanjutkan?')) {
                    form.submit();
                }
            });
        });

        /* =========================
           SEARCH HIGHLIGHT
        ========================= */
        const keyword = "{{ request('search') }}";
        if (keyword) {
            document.querySelectorAll('.warga-row').forEach(row => {
                row.innerHTML = row.innerHTML.replace(
                    new RegExp(keyword, 'gi'),
                    match => `<mark>${match}</mark>`
                );
            });
        }

        /* =========================
           LOADING BUTTON
        ========================= */
        document.querySelectorAll('button[type="submit"]').forEach(btn => {
            btn.addEventListener('click', () => {
                btn.innerText = 'Memproses...';
                btn.disabled = true;
            });
        });
    </script>
@endpush
