<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departemen;
use App\Models\Dosen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DosenController extends Controller
{
    /**
     * Tampilkan daftar dosen dengan pencarian, filter departemen, dan pagination.
     */
    public function index(Request $request): View
    {
        $dosens = Dosen::query()
            ->with('departemen')
            ->withCount('mahasiswas')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%");
                });
            })
            ->when($request->departemen_id, fn ($q, $id) => $q->where('departemen_id', $id))
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        $departemens = Departemen::orderBy('nama')->get();

        return view('admin.dosen.index', compact('dosens', 'departemens'));
    }

    /**
     * Hapus data dosen (dikunci oleh middleware permission:hapus-dosen).
     */
    public function destroy(Dosen $dosen): RedirectResponse
    {
        $dosen->delete();

        return redirect()
            ->route('admin.dosen.index')
            ->with('success', 'Dosen dihapus.');
    }
}
