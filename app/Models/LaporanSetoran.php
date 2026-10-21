<?php

namespace App\Models;

use Database\Factories\LaporanSetoranFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanSetoran extends Model
{
    /** @use HasFactory<LaporanSetoranFactory> */
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'laporan_setorans';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'waktu_setoran',
        'petugas_id',
        'nama_petugas',
        'siswa_id',
        'nama_siswa',
        'kelas',
        'jurusan',
        'tahun_ajaran',
        'kategori',
        'surah',
        'ayat',
        'nilai',
        'catatan',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'waktu_setoran' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Petugas, $this>
     */
    public function petugas(): BelongsTo
    {
        return $this->belongsTo(Petugas::class, 'petugas_id');
    }

    /**
     * @return BelongsTo<Siswa, $this>
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    /**
     * @return array{label: string, class: string, desc: string}
     */
    public static function predikat(int $nilai): array
    {
        if ($nilai >= 90) {
            return ['label' => 'Mumtaz', 'class' => 'predikat-mumtaz', 'desc' => 'Istimewa'];
        }
        if ($nilai >= 80) {
            return ['label' => 'Jayyid Jiddan', 'class' => 'predikat-jayyid-jiddan', 'desc' => 'Sangat Baik'];
        }
        if ($nilai >= 70) {
            return ['label' => 'Jayyid', 'class' => 'predikat-jayyid', 'desc' => 'Baik'];
        }
        if ($nilai >= 60) {
            return ['label' => 'Maqbul', 'class' => 'predikat-maqbul', 'desc' => 'Cukup'];
        }

        return ['label' => 'Dhaif', 'class' => 'predikat-dhaif', 'desc' => 'Perlu Bimbingan'];
    }

    /**
     * @return array{label: string, class: string, desc: string}
     */
    public function getPredikatAttribute(): array
    {
        return self::predikat((int) $this->nilai);
    }
}
