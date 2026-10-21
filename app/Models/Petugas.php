<?php

namespace App\Models;

use Database\Factories\PetugasFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Petugas extends Model
{
    /** @use HasFactory<PetugasFactory> */
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'petugas';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return HasMany<LaporanSetoran, $this>
     */
    public function laporanSetorans(): HasMany
    {
        return $this->hasMany(LaporanSetoran::class, 'petugas_id');
    }
}
