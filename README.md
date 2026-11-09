# Portal Akademik FTEIC ITS — Admin Dashboard & RBAC

Aplikasi Portal Akademik berbasis **Laravel 11** yang mengimplementasikan panel administrasi (**AdminLTE 3**) dengan kontrol akses berbasis peran (**Role-Based Access Control / RBAC**) menggunakan **Spatie Laravel Permission**, autentikasi **Laravel Breeze**, widget statistik dinamis, serta modul pencarian, penyaringan (`when()`), dan paginasi aman (`withQueryString()`).

Disusun untuk memenuhi tugas **Pemrograman Berbasis Kerangka Kerja (PBKK) — Pertemuan 10**, Departemen Teknik Informatika, Fakultas Teknologi Elektro dan Informatika Cerdas (FTEIC), Institut Teknologi Sepuluh Nopember (ITS).

---

## Fitur Utama

1. **Panel Admin Terpisah (`/admin/*`)**
   - Menggunakan master layout `@extends('adminlte::page')` dengan kustomisasi identitas visual ITS (`public/css/admin-custom.css`).
   - Pengalihan otomatis setelah login: pengguna berperan `admin` atau `staf` diarahkan ke `/admin/dashboard`, sedangkan pengguna biasa diarahkan ke `/home`.
   - Lencana peran aktif (`Admin` / `Staf`) ditampilkan di bilah atas (topbar) untuk memudahkan identifikasi saat demo multi-user.
2. **Kontrol Akses Berbasis Peran (Spatie RBAC)**
   - Proteksi berlapis: penyembunyian elemen UI dengan `@can` di Blade disertai penguncian wajib di sisi server menggunakan middleware `role:admin|staf` dan `permission:hapus-*`.
   - Halaman khusus `403 Akses Ditolak` berbahasa Indonesia dengan tautan kembali ke dashboard.
   - Konfirmasi penghapusan menggunakan dialog modal dengan fokus awal pada tombol **Batal**.
3. **Dashboard Statistik Real-Time**
   - Menampilkan 3 kartu widget setara yang dihitung langsung dari database menggunakan `count()`:
     - **Total mahasiswa** (`Mahasiswa::count()`)
     - **Total dosen** (`Dosen::count()`)
     - **Tugas aktif** (`Tugas::count()`)
4. **Pencarian, Filter, dan Paginasi Aman**
   - Pencarian multi-kolom (`nama` atau `nrp`) menggunakan `when()` yang dibungkus closure `where(function ($q) ...)` agar klausa `orWhere` tidak membocorkan filter lain.
   - Filter berdasarkan departemen (`departemen_id`) dan status akademik (`$request->filled('is_active')`).
   - Eager loading (`with(['departemen', 'dosen'])`) untuk mencegah masalah *N+1 query*, dilengkapi index database pada kolom `nrp`, `nama`, dan `nip`.
   - Paginasi 10 baris per halaman dengan `withQueryString()` sehingga parameter pencarian dan filter tetap terjaga saat berpindah halaman.
5. **Login Cepat Demo Lokal**
   - Tombol **Masuk sebagai admin** dan **Masuk sebagai staf** pada halaman `/login` yang hanya muncul apabila `app()->environment('local')` bernilai `true`.

---

## Langkah Instalasi

1. **Kloning repositori dan masuk ke direktori proyek**
   ```bash
   git clone <url-repositori-anda>
   cd Tugas_week_10
   ```

2. **Pasang dependensi PHP melalui Composer**
   ```bash
   composer install
   ```

3. **Salin berkas konfigurasi lingkungan dan buat kunci aplikasi**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Siapkan berkas database SQLite (default)**
   ```bash
   touch database/database.sqlite
   ```
   *(Pada Windows PowerShell: `New-Item -Path database/database.sqlite -ItemType File -Force`)*

5. **Jalankan migrasi dan seeder (peran, izin, akun uji, serta data Faker)**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Jalankan server pengembangan lokal**
   ```bash
   php artisan serve
   ```
   Buka `http://127.0.0.1:8000/login` pada peramban.

---

## Perintah Seeder & Pengujian

- **Menjalankan ulang seeder tanpa duplikasi (idempoten dengan `firstOrCreate`)**:
  ```bash
  php artisan db:seed
  ```
- **Menjalankan seluruh pengujian otomatis (PHPUnit Feature & Unit Test)**:
  ```bash
  php artisan test
  ```
- **Memeriksa kepatuhan gaya kode PSR-12 (Laravel Pint)**:
  ```bash
  ./vendor/bin/pint --test
  ```

---

## Tabel Akun Uji

Akun berikut dibuat secara otomatis oleh `RoleAndPermissionSeeder` (hanya untuk keperluan pengembangan lokal dan demo):

| Peran | Email | Kata Sandi | Hak Akses Utama |
|---|---|---|---|
| `admin` | `admin@example.test` | `password` | Akses penuh ke seluruh modul termasuk menghapus data (`hapus-mahasiswa`, `hapus-dosen`, `hapus-tugas`) |
| `staf` | `staf@example.test` | `password` | Akses melihat, menambah, dan mengubah data akademik, **tanpa** izin menghapus data (`hapus-*`) |

---

## Penjelasan Peran dan Izin (RBAC)

Seluruh peran dan izin disimpan di database menggunakan paket `spatie/laravel-permission` dengan format penamaan `kata-kerja-objek`:

| Nama Izin | Deskripsi | `admin` | `staf` |
|---|---|:---:|:---:|
| `lihat-dashboard` | Membuka halaman utama dashboard admin | Ya | Ya |
| `lihat-mahasiswa` | Melihat tabel, mencari, dan memfilter data mahasiswa | Ya | Ya |
| `tambah-mahasiswa` | Menambahkan data mahasiswa baru | Ya | Ya |
| `edit-mahasiswa` | Mengubah data mahasiswa | Ya | Ya |
| `hapus-mahasiswa` | Menghapus data mahasiswa (`DELETE /admin/mahasiswa/{mahasiswa}`) | **Ya** | **Tidak** |
| `lihat-dosen` | Melihat tabel dan mencari data dosen wali | Ya | Ya |
| `tambah-dosen` | Menambahkan data dosen | Ya | Ya |
| `edit-dosen` | Mengubah data dosen | Ya | Ya |
| `hapus-dosen` | Menghapus data dosen (`DELETE /admin/dosen/{dosen}`) | **Ya** | **Tidak** |
| `lihat-tugas` | Melihat daftar tugas akademik | Ya | Ya |
| `tambah-tugas` | Membuat tugas baru | Ya | Ya |
| `edit-tugas` | Mengubah tugas dan nilai | Ya | Ya |
| `hapus-tugas` | Menghapus tugas (`DELETE /admin/tugas/{tugas}`) | **Ya** | **Tidak** |
| `kelola-nilai` | Mengelola penilaian tugas akademik | Ya | Ya |
| `tambah-nilai` | Menambahkan nilai tugas | Ya | Ya |

---

## Tangkapan Layar (Screenshot) Dashboard

> **Catatan:** Tangkapan layar diambil secara manual melalui peramban setelah menjalankan `php artisan serve`. Silakan simpan berkas gambar pada `docs/screenshots/` dan perbarui tautan di bawah ini sebelum pengumpulan akhir.

### 1. Beranda Dashboard Admin (`/admin/dashboard`)
![Placeholder Screenshot Dashboard Admin](docs/screenshots/dashboard-admin.png)
*(Catatan: Screenshot diambil secara manual menampilkan 3 kartu widget statistik sejajar, sidebar Tinta Laut, dan lencana peran di topbar.)*

### 2. Perbedaan Tampilan Aksi Hapus: Admin vs Staf (`/admin/tugas`)
![Placeholder Screenshot Perbandingan Admin dan Staf](docs/screenshots/perbandingan-admin-staf.png)
*(Catatan: Screenshot diambil secara manual menampilkan tombol "Hapus tugas" beserta modal konfirmasi pada akun Admin, serta ketiadaan tombol hapus pada akun Staf.)*

### 3. Modul Tabel Mahasiswa dengan Pencarian & Filter (`/admin/mahasiswa`)
![Placeholder Screenshot Tabel Mahasiswa](docs/screenshots/tabel-mahasiswa-filter.png)
*(Catatan: Screenshot diambil secara manual menampilkan bilah pencarian nama/NRP, filter departemen, filter status Aktif/Cuti, dan paginasi yang menjaga query string.)*
