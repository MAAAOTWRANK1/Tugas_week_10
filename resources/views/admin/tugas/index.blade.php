@extends('adminlte::page')

@section('title', 'Daftar tugas — Portal Akademik FTEIC ITS')

@section('content_top_nav_right')
    @include('admin.partials.topbar-role')
@endsection

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <h1 class="page-title">Daftar tugas</h1>
        @can('tambah-tugas')
            <a href="{{ route('admin.tugas.create') }}" class="btn btn-secondary-outline">
                Tambah tugas
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
            <form method="GET" action="{{ route('admin.tugas.index') }}" class="form-row align-items-end">
                <div class="col-md-5 mb-2 mb-md-0">
                    <label for="search" class="font-weight-bold mb-1">Kata kunci</label>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Cari judul tugas atau mata kuliah"
                    >
                </div>

                <div class="col-md-4 mb-2 mb-md-0">
                    <label for="dosen_id" class="font-weight-bold mb-1">Dosen pengampu</label>
                    <select id="dosen_id" name="dosen_id" class="form-control">
                        <option value="">Semua dosen</option>
                        @foreach ($dosens as $dosen)
                            <option
                                value="{{ $dosen->id }}"
                                @selected((string) request('dosen_id') === (string) $dosen->id)
                            >
                                {{ $dosen->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-center">
                    <button type="submit" class="btn btn-primary mr-2">Terapkan filter</button>
                    @if (request()->filled('search') || request()->filled('dosen_id'))
                        <a href="{{ route('admin.tugas.index') }}" class="btn btn-secondary-outline">
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
                            <th scope="col">Judul tugas</th>
                            <th scope="col">Mata kuliah</th>
                            <th scope="col">Dosen pengampu</th>
                            <th scope="col">Tenggat</th>
                            <th scope="col" class="text-right">Nilai</th>
                            <th scope="col" class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tugasList as $tugas)
                            <tr>
                                <td>
                                    <div class="font-weight-bold">{{ $tugas->judul }}</div>
                                    @if ($tugas->mahasiswa)
                                        <div class="text-secondary-academic">Mahasiswa: {{ $tugas->mahasiswa->nama }}</div>
                                    @endif
                                </td>
                                <td>{{ $tugas->mata_kuliah }}</td>
                                <td>{{ $tugas->dosen->nama ?? '-' }}</td>
                                <td class="tabular-nums">{{ $tugas->tenggat?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td class="text-right tabular-nums">{{ $tugas->nilai ?? '-' }}</td>
                                <td class="text-right">
                                    <div class="d-inline-flex align-items-center justify-content-end">
                                        @can('edit-tugas')
                                            <a href="{{ route('admin.tugas.edit', $tugas) }}" class="link-action-edit mr-2">
                                                Ubah
                                            </a>
                                        @endcan

                                        @can('hapus-tugas')
                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                data-toggle="modal"
                                                data-target="#modalHapusTugas-{{ $tugas->id }}"
                                            >
                                                Hapus tugas
                                            </button>

                                            <div
                                                class="modal fade text-left"
                                                id="modalHapusTugas-{{ $tugas->id }}"
                                                tabindex="-1"
                                                role="dialog"
                                                aria-labelledby="labelHapusTugas-{{ $tugas->id }}"
                                                aria-hidden="true"
                                            >
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title font-weight-bold" id="labelHapusTugas-{{ $tugas->id }}">
                                                                Hapus tugas ini?
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Batal">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="mb-1">Data yang dihapus tidak bisa dikembalikan.</p>
                                                            <p class="text-secondary-academic mb-0">Tugas: <strong>{{ $tugas->judul }}</strong></p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button
                                                                type="button"
                                                                class="btn btn-secondary-outline btn-cancel-focus"
                                                                data-dismiss="modal"
                                                                autofocus
                                                            >
                                                                Batal
                                                            </button>
                                                            <form method="POST" action="{{ route('admin.tugas.destroy', $tugas) }}" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">
                                                                    Hapus tugas
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-secondary-academic">
                                    Tidak ada tugas yang cocok dengan filter ini. Ubah kata kunci atau atur ulang filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($tugasList->hasPages())
            <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap">
                <span class="text-secondary-academic tabular-nums">
                    Menampilkan {{ $tugasList->firstItem() }}–{{ $tugasList->lastItem() }} dari {{ $tugasList->total() }} tugas
                </span>
                <div>
                    {{ $tugasList->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif
    </div>
@endsection

@section('js')
    <script>
        $(document).on('shown.bs.modal', '.modal', function () {
            $(this).find('.btn-cancel-focus').trigger('focus');
        });
    </script>
@endsection
