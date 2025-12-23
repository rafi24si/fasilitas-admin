@extends('layouts.admin.app')

@section('content')
<div class="container py-5 text-center">
    <h1 class="display-3 text-danger fw-bold">403</h1>
    <p class="fs-4">Akses Ditolak</p>
    <p class="text-muted">Anda tidak memiliki izin untuk mengakses halaman ini.</p>

    <a href="{{ route('dashboard') }}" class="btn btn-primary px-4">
        Kembali ke Dashboard
    </a>
</div>
@endsection
