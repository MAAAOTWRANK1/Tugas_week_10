<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan index pencarian pada kolom nrp, nama, dan nip.
     */
    public function up(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->index('nrp', 'mahasiswas_nrp_index');
            $table->index('nama', 'mahasiswas_nama_index');
        });

        Schema::table('dosens', function (Blueprint $table) {
            $table->index('nip', 'dosens_nip_index');
            $table->index('nama', 'dosens_nama_index');
        });
    }

    /**
     * Hapus index pencarian.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropIndex('mahasiswas_nrp_index');
            $table->dropIndex('mahasiswas_nama_index');
        });

        Schema::table('dosens', function (Blueprint $table) {
            $table->dropIndex('dosens_nip_index');
            $table->dropIndex('dosens_nama_index');
        });
    }
};
