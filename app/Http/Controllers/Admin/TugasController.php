<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTugasRequest;
use App\Http\Requests\Admin\UpdateTugasRequest;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Tugas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TugasController extends Controller
{
    /**
     * Tampilkan daftar tugas akademik dengan pencarian dan pagination.
     */
    public function index(Request $request): View
    {
        $tugasList = Tugas::query()
            ->with(['dosen', 'mahasiswa'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                        ->orWhere('mata_kuliah', 'like', "%{$search}%");
                });
            })
            ->when($request->dosen_id, fn ($q, $id) => $q->where('dosen_id', $id))
            ->orderByDesc('tenggat')
            ->paginate(10)
            ->withQueryString();

        $dosens = Dosen::orderBy('nama')->get();

        return view('admin.tugas.index', compact('tugasList', 'dosens'));
    }

    /**
     * Tampilkan form tambah tugas.
     */
    public function create(): View
    {
        $dosens = Dosen::orderBy('nama')->get();
        $mahasiswas = Mahasiswa::orderBy('nama')->get();

        return view('admin.tugas.create', compact('dosens', 'mahasiswas'));
    }

    /**
     * Simpan tugas baru.
     */
    public function store(StoreTugasRequest $request): RedirectResponse
    {
        Tugas::create($request->validated());

        return redirect()
            ->route('admin.tugas.index')
            ->with('success', 'Tugas berhasil ditambahkan.');
    }

    /**
     * Tampilkan form ubah tugas.
     */
    public function edit(Tugas $tugas): View
    {
        $dosens = Dosen::orderBy('nama')->get();
        $mahasiswas = Mahasiswa::orderBy('nama')->get();

        return view('admin.tugas.edit', compact('tugas', 'dosens', 'mahasiswas'));
    }

    /**
     * Simpan perubahan tugas.
     */
    public function update(UpdateTugasRequest $request, Tugas $tugas): RedirectResponse
    {
        $tugas->update($request->validated());

        return redirect()
            ->route('admin.tugas.index')
            ->with('success', 'Perubahan tugas disimpan.');
    }

    /**
     * Hapus data tugas (dikunci oleh middleware permission:hapus-tugas).
     */
    public function destroy(Tugas $tugas): RedirectResponse
    {
        $tugas->delete();

        return redirect()
            ->route('admin.tugas.index')
            ->with('success', 'Tugas dihapus.');
    }
}
