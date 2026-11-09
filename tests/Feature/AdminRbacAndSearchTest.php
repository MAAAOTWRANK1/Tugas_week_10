<?php

namespace Tests\Feature;

use App\Models\Departemen;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Tugas;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRbacAndSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_login_redirects_admin_to_admin_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@example.test',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_login_redirects_staf_to_admin_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'staf@example.test',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_login_redirects_user_without_role_to_home(): void
    {
        $user = User::factory()->create([
            'email' => 'pengguna@example.test',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('home'));
    }

    public function test_user_without_role_gets_403_when_accessing_admin_routes(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(403);
        $response->assertSee('Anda tidak punya izin membuka halaman ini.');
    }

    public function test_staf_cannot_see_delete_button_and_gets_403_on_delete_request(): void
    {
        $staf = User::where('email', 'staf@example.test')->firstOrFail();

        $tugas = Tugas::create([
            'judul' => 'Tugas Praktikum PBKK',
            'mata_kuliah' => 'PBKK',
            'tenggat' => now()->addDays(5),
        ]);

        $pageResponse = $this->actingAs($staf)->get(route('admin.tugas.index'));
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee('Tugas Praktikum PBKK');
        $pageResponse->assertDontSee('Hapus tugas');

        $deleteResponse = $this->actingAs($staf)->delete(route('admin.tugas.destroy', $tugas));
        $deleteResponse->assertStatus(403);

        $this->assertDatabaseHas('tugas', ['id' => $tugas->id]);
    }

    public function test_admin_can_delete_tugas_and_mahasiswa(): void
    {
        $admin = User::where('email', 'admin@example.test')->firstOrFail();

        $departemen = Departemen::create([
            'kode' => 'IF',
            'nama' => 'Teknik Informatika',
        ]);

        $mahasiswa = Mahasiswa::create([
            'nrp' => '5025211001',
            'nama' => 'Raka Pratama',
            'email' => 'raka@mhs.its.ac.id',
            'departemen_id' => $departemen->id,
            'is_active' => true,
        ]);

        $tugas = Tugas::create([
            'judul' => 'Tugas Akhir Bab 1',
            'mata_kuliah' => 'PBKK',
            'tenggat' => now()->addDays(3),
            'mahasiswa_id' => $mahasiswa->id,
        ]);

        $pageResponse = $this->actingAs($admin)->get(route('admin.tugas.index'));
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee('Hapus tugas');

        $deleteTugasResponse = $this->actingAs($admin)->delete(route('admin.tugas.destroy', $tugas));
        $deleteTugasResponse->assertRedirect(route('admin.tugas.index'));
        $this->assertDatabaseMissing('tugas', ['id' => $tugas->id]);

        $deleteMahasiswaResponse = $this->actingAs($admin)->delete(route('admin.mahasiswa.destroy', $mahasiswa));
        $deleteMahasiswaResponse->assertRedirect(route('admin.mahasiswa.index'));
        $this->assertDatabaseMissing('mahasiswas', ['id' => $mahasiswa->id]);
    }

    public function test_dashboard_widgets_display_dynamic_counts_from_database(): void
    {
        $admin = User::where('email', 'admin@example.test')->firstOrFail();

        $departemen = Departemen::create([
            'kode' => 'IF',
            'nama' => 'Teknik Informatika',
        ]);

        $dosen = Dosen::create([
            'nip' => '198001012005011001',
            'nama' => 'Dr. Hendra Wijaya',
            'email' => 'hendra@its.ac.id',
            'departemen_id' => $departemen->id,
        ]);

        Mahasiswa::create([
            'nrp' => '5025211010',
            'nama' => 'Siti Aminah',
            'email' => 'siti@mhs.its.ac.id',
            'departemen_id' => $departemen->id,
            'dosen_id' => $dosen->id,
            'is_active' => true,
        ]);

        Mahasiswa::create([
            'nrp' => '5025211011',
            'nama' => 'Dimas Anggara',
            'email' => 'dimas@mhs.its.ac.id',
            'departemen_id' => $departemen->id,
            'dosen_id' => $dosen->id,
            'is_active' => true,
        ]);

        Tugas::create([
            'judul' => 'Implementasi RBAC',
            'mata_kuliah' => 'PBKK',
            'tenggat' => now()->addDays(7),
            'dosen_id' => $dosen->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('totalMahasiswa', 2);
        $response->assertViewHas('totalDosen', 1);
        $response->assertViewHas('totalTugas', 1);
    }

    public function test_mahasiswa_search_and_filter_with_when_returns_accurate_results(): void
    {
        $staf = User::where('email', 'staf@example.test')->firstOrFail();

        $deptIf = Departemen::create(['kode' => 'IF', 'nama' => 'Teknik Informatika']);
        $deptSi = Departemen::create(['kode' => 'SI', 'nama' => 'Sistem Informasi']);

        Mahasiswa::create([
            'nrp' => '5025211001',
            'nama' => 'Andi Wijaya',
            'email' => 'andi.if@mhs.its.ac.id',
            'departemen_id' => $deptIf->id,
            'is_active' => true,
        ]);

        Mahasiswa::create([
            'nrp' => '5025211002',
            'nama' => 'Andi Cuti',
            'email' => 'andi.cuti@mhs.its.ac.id',
            'departemen_id' => $deptIf->id,
            'is_active' => false,
        ]);

        Mahasiswa::create([
            'nrp' => '5026211003',
            'nama' => 'Andi Sistem',
            'email' => 'andi.si@mhs.its.ac.id',
            'departemen_id' => $deptSi->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($staf)->get(route('admin.mahasiswa.index', [
            'search' => 'Andi',
            'departemen_id' => $deptIf->id,
            'is_active' => '0',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Andi Cuti');
        $response->assertDontSee('Andi Wijaya');
        $response->assertDontSee('Andi Sistem');
    }

    public function test_mahasiswa_pagination_links_preserve_query_string(): void
    {
        $admin = User::where('email', 'admin@example.test')->firstOrFail();

        $deptIf = Departemen::create(['kode' => 'IF', 'nama' => 'Teknik Informatika']);

        for ($i = 1; $i <= 15; $i++) {
            Mahasiswa::create([
                'nrp' => sprintf('50252110%02d', $i),
                'nama' => 'Mahasiswa Filter ' . $i,
                'email' => "mhs{$i}@mhs.its.ac.id",
                'departemen_id' => $deptIf->id,
                'is_active' => true,
            ]);
        }

        $response = $this->actingAs($admin)->get(route('admin.mahasiswa.index', [
            'search' => 'Filter',
            'departemen_id' => $deptIf->id,
            'is_active' => '1',
        ]));

        $response->assertStatus(200);
        $content = $response->getContent();

        $this->assertStringContainsString('search=Filter', $content);
        $this->assertStringContainsString('departemen_id=' . $deptIf->id, $content);
        $this->assertStringContainsString('is_active=1', $content);
        $this->assertStringContainsString('page=2', $content);
    }
}
