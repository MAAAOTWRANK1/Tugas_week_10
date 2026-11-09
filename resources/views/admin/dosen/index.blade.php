@extends('adminlte::page')

@section('title', 'Data dosen — Portal Akademik FTEIC ITS')

@section('content_top_nav_right')
    @include('admin.partials.topbar-role')
@endsection

@section('content_header')
    <h1 class="page-title">Data dosen</h1>
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
            <form method="GET" action="{{ route('admin.dosen.index') }}" class="form-row align-items-end">
                <div class="col-md-5 mb-2 mb-md-0">
                    <label for="search" class="font-weight-bold mb-1">Kata kunci</label>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Cari nama atau NIP"
                    >
                </div>

                <div class="col-md-4 mb-2 mb-md-0">
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

                <div class="col-md-3 d-flex align-items-center">
                    <button type="submit" class="btn btn-primary mr-2">Terapkan filter</button>
                    @if (request()->filled('search') || request()->filled('departemen_id'))
                        <a href="{{ route('admin.dosen.index') }}" class="btn btn-secondary-outline">
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
                            <th scope="col">NIP</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Departemen</th>
                            <th scope="col" class="text-right">Mahasiswa wali</th>
                            <th scope="col" class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dosens as $dosen)
                            <tr>
                                <td class="tabular-nums font-weight-bold">{{ $dosen->nip }}</td>
                                <td>
                                    <div>{{ $dosen->nama }}</div>
                                    <div class="text-secondary-academic">{{ $dosen->email }}</div>
                                </td>
                                <td>{{ $dosen->departemen->nama ?? '-' }}</td>
                                <td class="text-right tabular-nums">{{ $dosen->mahasiswas_count }}</td>
                                <td class="text-right">
                                    @can('hapus-dosen')
                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm"
                                            data-toggle="modal"
                                            data-target="#modalHapusDosen-{{ $dosen->id }}"
                                        >
                                            Hapus dosen
                                        </button>

                                        <div
                                            class="modal fade text-left"
                                            id="modalHapusDosen-{{ $dosen->id }}"
                                            tabindex="-1"
                                            role="dialog"
                                            aria-labelledby="labelHapusDosen-{{ $dosen->id }}"
                                            aria-hidden="true"
                                        >
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title font-weight-bold" id="labelHapusDosen-{{ $dosen->id }}">
                                                            Hapus dosen ini?
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Batal">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-1">Data yang dihapus tidak bisa dikembalikan.</p>
                                                        <p class="text-secondary-academic mb-0">Dosen: <strong>{{ $dosen->nama }}</strong></p>
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
                                                        <form method="POST" action="{{ route('admin.dosen.destroy', $dosen) }}" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">
                                                                Hapus dosen
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-secondary-academic">
                                    Tidak ada dosen yang cocok dengan filter ini. Ubah kata kunci atau atur ulang filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($dosens->hasPages())
            <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap">
                <span class="text-secondary-academic tabular-nums">
                    Menampilkan {{ $dosens->firstItem() }}–{{ $dosens->lastItem() }} dari {{ $dosens->total() }} dosen
                </span>
                <div>
                    {{ $dosens->links('pagination::bootstrap-4') }}
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
