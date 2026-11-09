<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Tugas;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman utama dashboard admin dengan statistik nyata dari database.
     */
    public function index(): View
    {
        $totalMahasiswa = Mahasiswa::count();
        $totalDosen = Dosen::count();
        $totalTugas = Tugas::count();

        return view('admin.dashboard', compact(
            'totalMahasiswa',
            'totalDosen',
            'totalTugas'
        ));
    }
}
