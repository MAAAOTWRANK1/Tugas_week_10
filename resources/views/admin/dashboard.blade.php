@extends('adminlte::page')

@section('title', 'Dashboard — Portal Akademik FTEIC ITS')

@section('content_top_nav_right')
    @include('admin.partials.topbar-role')
@endsection

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="page-title">Dashboard</h1>
        <span class="text-secondary-academic">Ringkasan data akademik FTEIC ITS</span>
    </div>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-academic-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="close text-white" data-dismiss="alert" aria-label="Tutup notifikasi">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-4 mb-3">
            <a href="{{ url('admin/mahasiswa') }}" class="stat-widget-card">
                <div class="stat-widget-label">Total mahasiswa</div>
                <div class="stat-widget-value tabular-nums" data-testid="widget-mahasiswa">
                    {{ number_format($totalMahasiswa, 0, ',', '.') }}
                </div>
                <p class="stat-widget-desc">Terdaftar di database</p>
            </a>
        </div>

        <div class="col-md-4 mb-3">
            <a href="{{ url('admin/dosen') }}" class="stat-widget-card">
                <div class="stat-widget-label">Total dosen</div>
                <div class="stat-widget-value tabular-nums" data-testid="widget-dosen">
                    {{ number_format($totalDosen, 0, ',', '.') }}
                </div>
                <p class="stat-widget-desc">Dosen wali FTEIC</p>
            </a>
        </div>

        <div class="col-md-4 mb-3">
            <a href="{{ url('admin/tugas') }}" class="stat-widget-card">
                <div class="stat-widget-label">Tugas aktif</div>
                <div class="stat-widget-value tabular-nums" data-testid="widget-tugas">
                    {{ number_format($totalTugas, 0, ',', '.') }}
                </div>
                <p class="stat-widget-desc">Belum melewati tenggat</p>
            </a>
        </div>
    </div>
@endsection
