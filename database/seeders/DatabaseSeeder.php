<?php

namespace Database\Seeders;

use App\Models\Departemen;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Tugas;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
        ]);

        $daftarDepartemen = [
            ['kode' => 'IF', 'nama' => 'Teknik Informatika'],
            ['kode' => 'SI', 'nama' => 'Sistem Informasi'],
            ['kode' => 'TE', 'nama' => 'Teknik Elektro'],
            ['kode' => 'TK', 'nama' => 'Teknik Komputer'],
            ['kode' => 'TB', 'nama' => 'Teknik Biomedik'],
            ['kode' => 'TI', 'nama' => 'Teknologi Informasi'],
        ];

        $departemens = collect($daftarDepartemen)->map(
            fn (array $dept) => Departemen::firstOrCreate(
                ['kode' => $dept['kode']],
                ['nama' => $dept['nama']]
            )
        );

        // Hanya buat data Faker jika tabel masih kosong agar db:seed aman dijalankan ulang
        if (Dosen::count() === 0) {
            $dosens = collect();
            foreach ($departemens as $departemen) {
                $createdDosens = Dosen::factory(4)->create([
                    'departemen_id' => $departemen->id,
                ]);
                $dosens = $dosens->merge($createdDosens);
            }

            $mahasiswas = collect();
            foreach ($departemens as $departemen) {
                $dosenDepartemen = $dosens->where('departemen_id', $departemen->id)->values();
                $createdMahasiswas = Mahasiswa::factory(8)->create([
                    'departemen_id' => $departemen->id,
                    'dosen_id' => fn () => $dosenDepartemen->random()->id,
                ]);
                $mahasiswas = $mahasiswas->merge($createdMahasiswas);
            }

            Tugas::factory(30)->create([
                'dosen_id' => fn () => $dosens->random()->id,
                'mahasiswa_id' => fn () => $mahasiswas->random()->id,
            ]);
        }
    }
}
