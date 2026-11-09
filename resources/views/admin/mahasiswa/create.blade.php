@extends('adminlte::page')

@section('title', 'Tambah mahasiswa — Portal Akademik FTEIC ITS')

@section('content_top_nav_right')
    @include('admin.partials.topbar-role')
@endsection

@section('content_header')
    <h1 class="page-title">Tambah mahasiswa</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.mahasiswa.store') }}">
                @csrf

                <div class="form-group">
                    <label for="nrp" class="font-weight-bold">NRP <span class="text-secondary-academic">(wajib)</span></label>
                    <input type="text" id="nrp" name="nrp" value="{{ old('nrp') }}" class="form-control @error('nrp') is-invalid @enderror">
                    @error('nrp')
                        <div class="mt-1" style="color: var(--c-danger); font-size: 13px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nama" class="font-weight-bold">Nama lengkap <span class="text-secondary-academic">(wajib)</span></label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror">
                    @error('nama')
                        <div class="mt-1" style="color: var(--c-danger); font-size: 13px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="font-weight-bold">Email institusi <span class="text-secondary-academic">(wajib)</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                        <div class="mt-1" style="color: var(--c-danger); font-size: 13px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="departemen_id" class="font-weight-bold">Departemen <span class="text-secondary-academic">(wajib)</span></label>
                    <select id="departemen_id" name="departemen_id" class="form-control @error('departemen_id') is-invalid @enderror">
                        <option value="">Pilih departemen</option>
                        @foreach ($departemens as $departemen)
                            <option value="{{ $departemen->id }}" @selected((string) old('departemen_id') === (string) $departemen->id)>
                                {{ $departemen->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('departemen_id')
                        <div class="mt-1" style="color: var(--c-danger); font-size: 13px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="dosen_id" class="font-weight-bold">Dosen wali</label>
                    <select id="dosen_id" name="dosen_id" class="form-control">
                        <option value="">Tanpa dosen wali</option>
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}" @selected((string) old('dosen_id') === (string) $dosen->id)>
                                {{ $dosen->nama }} ({{ $dosen->nip }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="is_active" class="font-weight-bold">Status akademik <span class="text-secondary-academic">(wajib)</span></label>
                    <select id="is_active" name="is_active" class="form-control">
                        <option value="1" @selected(old('is_active', '1') === '1')>Aktif</option>
                        <option value="0" @selected(old('is_active') === '0')>Cuti</option>
                    </select>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary mr-2">Simpan perubahan</button>
                    <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-secondary-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
