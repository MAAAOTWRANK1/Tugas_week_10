@extends('adminlte::page')

@section('title', 'Data mahasiswa — Portal Akademik FTEIC ITS')

@section('content_top_nav_right')
    @include('admin.partials.topbar-role')
@endsection

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <h1 class="page-title">Data mahasiswa</h1>
        @can('tambah-mahasiswa')
            <a href="{{ route('admin.mahasiswa.create') }}" class="btn btn-secondary-outline">
                Tambah mahasiswa
            </a>
        @endcan
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

    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('admin.mahasiswa.index') }}" class="form-row align-items-end">
                <div class="col-md-4 mb-2 mb-md-0">
                    <label for="search" class="font-weight-bold mb-1">Kata kunci</label>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Cari nama atau NRP"
                    >
                </div>

                <div class="col-md-3 mb-2 mb-md-0">
                    <label for="departemen_id" class="font-weight-bold mb-1">Departemen</label>
                    <select id="departemen_id" name="departemen_id" class="form-control">
                        <option value="">Semua departemen</option>
                        @foreach ($departemens as $departemen)
                            <option
                                value="{{ $departemen->id }}"
                                @selected((string) request('departemen_id') === (string) $departemen->id)
                            >
                                {{ $departemen->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-2 mb-md-0">
                    <label for="is_active" class="font-weight-bold mb-1">Status</label>
                    <select id="is_active" name="is_active" class="form-control">
                        <option value="">Semua</option>
                        <option value="1" @selected(request('is_active') === '1')>Aktif</option>
                        <option value="0" @selected(request('is_active') === '0')>Cuti</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-center">
                    <button type="submit" class="btn btn-primary mr-2">Terapkan filter</button>
                    @if (request()->filled('search') || request()->filled('departemen_id') || request()->filled('is_active'))
                        <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-secondary-outline">
                            Atur ulang
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table-academic">
                    <thead>
                        <tr>
                            <th scope="col">NRP</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Departemen</th>
                            <th scope="col">Dosen wali</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mahasiswas as $mahasiswa)
                            <tr>
                                <td class="tabular-nums font-weight-bold">{{ $mahasiswa->nrp }}</td>
                                <td>
                                    <div>{{ $mahasiswa->nama }}</div>
                                    <div class="text-secondary-academic">{{ $mahasiswa->email }}</div>
                                </td>
                                <td>{{ $mahasiswa->departemen->nama ?? '-' }}</td>
                                <td>{{ $mahasiswa->dosen->nama ?? '-' }}</td>
                                <td>
                                    @if ($mahasiswa->is_active)
                                        <span class="badge-status badge-status-active">Aktif</span>
                                    @else
                                        <span class="badge-status badge-status-cuti">Cuti</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @can('edit-mahasiswa')
                                        <a href="{{ route('admin.mahasiswa.edit', $mahasiswa) }}" class="link-action-edit">
                                            Ubah
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-secondary-academic">
                                    Tidak ada mahasiswa yang cocok dengan filter ini. Ubah kata kunci atau atur ulang filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($mahasiswas->hasPages())
            <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap">
                <span class="text-secondary-academic tabular-nums">
                    Menampilkan {{ $mahasiswas->firstItem() }}–{{ $mahasiswas->lastItem() }} dari {{ $mahasiswas->total() }} mahasiswa
                </span>
                <div>
                    {{ $mahasiswas->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif
    </div>
@endsection
