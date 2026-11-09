<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Daftarkan peran, izin, dan akun uji secara idempoten.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'lihat-dashboard',
            'lihat-mahasiswa',
            'tambah-mahasiswa',
            'edit-mahasiswa',
            'hapus-mahasiswa',
            'lihat-dosen',
            'tambah-dosen',
            'edit-dosen',
            'hapus-dosen',
            'lihat-tugas',
            'tambah-tugas',
            'edit-tugas',
            'hapus-tugas',
            'kelola-nilai',
            'tambah-nilai',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $stafRole = Role::firstOrCreate(['name' => 'staf']);

        // Admin mendapat seluruh izin termasuk hapus-*
        $adminRole->syncPermissions($permissions);

        // Staf mendapat izin operasional tanpa izin hapus-*
        $stafPermissions = array_values(array_filter(
            $permissions,
            fn (string $permission): bool => ! str_starts_with($permission, 'hapus-')
        ));
        $stafRole->syncPermissions($stafPermissions);

        // Buat akun uji admin dan staf menggunakan firstOrCreate
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.test'],
            [
                'name' => 'Admin Akademik',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $adminUser->syncRoles(['admin']);

        $stafUser = User::firstOrCreate(
            ['email' => 'staf@example.test'],
            [
                'name' => 'Staf Akademik',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $stafUser->syncRoles(['staf']);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
