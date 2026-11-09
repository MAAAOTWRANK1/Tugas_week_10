<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';

    protected $fillable = [
        'judul',
        'deskripsi',
        'mata_kuliah',
        'tenggat',
        'dosen_id',
        'mahasiswa_id',
        'nilai',
    ];

    protected function casts(): array
    {
        return [
            'tenggat' => 'datetime',
            'nilai' => 'integer',
        ];
    }

    /**
     * Relasi ke dosen pengampu tugas.
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    /**
     * Relasi ke mahasiswa penerima tugas (opsional).
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
