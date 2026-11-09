@extends('adminlte::page')

@section('title', 'Tambah tugas — Portal Akademik FTEIC ITS')

@section('content_top_nav_right')
    @include('admin.partials.topbar-role')
@endsection

@section('content_header')
    <h1 class="page-title">Tambah tugas</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.tugas.store') }}">
                @csrf

                <div class="form-group">
                    <label for="judul" class="font-weight-bold">Judul tugas <span class="text-secondary-academic">(wajib)</span></label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul') }}" class="form-control @error('judul') is-invalid @enderror">
                    @error('judul')
                        <div class="mt-1" style="color: var(--c-danger); font-size: 13px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="mata_kuliah" class="font-weight-bold">Mata kuliah <span class="text-secondary-academic">(wajib)</span></label>
                    <input type="text" id="mata_kuliah" name="mata_kuliah" value="{{ old('mata_kuliah', 'Pemrograman Berbasis Kerangka Kerja') }}" class="form-control @error('mata_kuliah') is-invalid @enderror">
                    @error('mata_kuliah')
                        <div class="mt-1" style="color: var(--c-danger); font-size: 13px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="deskripsi" class="font-weight-bold">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3" class="form-control">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="tenggat" class="font-weight-bold">Tenggat pengumpulan <span class="text-secondary-academic">(wajib)</span></label>
                    <input type="datetime-local" id="tenggat" name="tenggat" value="{{ old('tenggat') }}" class="form-control @error('tenggat') is-invalid @enderror">
                    @error('tenggat')
                        <div class="mt-1" style="color: var(--c-danger); font-size: 13px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="dosen_id" class="font-weight-bold">Dosen pengampu</label>
                    <select id="dosen_id" name="dosen_id" class="form-control">
                        <option value="">Pilih dosen</option>
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}" @selected((string) old('dosen_id') === (string) $dosen->id)>
                                {{ $dosen->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="mahasiswa_id" class="font-weight-bold">Mahasiswa terkait</label>
                    <select id="mahasiswa_id" name="mahasiswa_id" class="form-control">
                        <option value="">Seluruh kelas / umum</option>
                        @foreach ($mahasiswas as $mahasiswa)
                            <option value="{{ $mahasiswa->id }}" @selected((string) old('mahasiswa_id') === (string) $mahasiswa->id)>
                                {{ $mahasiswa->nrp }} — {{ $mahasiswa->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="nilai" class="font-weight-bold">Nilai (0–100)</label>
                    <input type="number" id="nilai" name="nilai" min="0" max="100" value="{{ old('nilai') }}" class="form-control @error('nilai') is-invalid @enderror">
                    @error('nilai')
                        <div class="mt-1" style="color: var(--c-danger); font-size: 13px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary mr-2">Simpan perubahan</button>
                    <a href="{{ route('admin.tugas.index') }}" class="btn btn-secondary-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
