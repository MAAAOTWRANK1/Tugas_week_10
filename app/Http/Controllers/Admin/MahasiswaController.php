<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMahasiswaRequest;
use App\Http\Requests\Admin\UpdateMahasiswaRequest;
use App\Models\Departemen;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MahasiswaController extends Controller
{
    /**
     * Tampilkan daftar mahasiswa dengan pencarian, filter, dan pagination.
     */
    public function index(Request $request): View
    {
        $mahasiswas = Mahasiswa::query()
            ->with(['departemen', 'dosen'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('nrp', 'like', "%{$search}%");
                });
            })
            ->when($request->departemen_id, fn ($q, $id) => $q->where('departemen_id', $id))
            ->when($request->filled('is_active'), fn ($q) => $q->where('is_active', $request->boolean('is_active')))
            ->orderBy('nrp')
            ->paginate(10)
            ->withQueryString();

        $departemens = Departemen::orderBy('nama')->get();

        return view('admin.mahasiswa.index', compact('mahasiswas', 'departemens'));
    }

    /**
     * Tampilkan form tambah mahasiswa.
     */
    public function create(): View
    {
        $departemens = Departemen::orderBy('nama')->get();
        $dosens = Dosen::orderBy('nama')->get();

        return view('admin.mahasiswa.create', compact('departemens', 'dosens'));
    }

    /**
     * Simpan data mahasiswa baru.
     */
    public function store(StoreMahasiswaRequest $request): RedirectResponse
    {
        Mahasiswa::create($request->validated());

        return redirect()
            ->route('admin.mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    /**
     * Tampilkan form ubah mahasiswa.
     */
    public function edit(Mahasiswa $mahasiswa): View
    {
        $departemens = Departemen::orderBy('nama')->get();
        $dosens = Dosen::orderBy('nama')->get();

        return view('admin.mahasiswa.edit', compact('mahasiswa', 'departemens', 'dosens'));
    }

    /**
     * Simpan perubahan data mahasiswa.
     */
    public function update(UpdateMahasiswaRequest $request, Mahasiswa $mahasiswa): RedirectResponse
    {
        $mahasiswa->update($request->validated());

        return redirect()
            ->route('admin.mahasiswa.index')
            ->with('success', 'Perubahan data mahasiswa disimpan.');
    }
}
