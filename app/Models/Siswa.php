<?php

namespace App\Models;

use Database\Factories\SiswaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    /** @use HasFactory<SiswaFactory> */
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'siswas';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'kelas',
        'jurusan',
        'tahun_ajaran',
    ];

    /**
     * @return HasMany<LaporanSetoran, $this>
     */
    public function laporanSetorans(): HasMany
    {
        return $this->hasMany(LaporanSetoran::class, 'siswa_id');
    }
}
