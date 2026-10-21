<?php

namespace Database\Seeders;

use App\Models\LaporanSetoran;
use App\Models\Petugas;
use App\Models\Siswa;
use App\Models\User;
use App\Support\Quran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Default
        User::updateOrCreate(
            ['email' => 'admin@man1probolinggo.sch.id'],
            [
                'name' => 'Administrator Tahfidz MAN 1',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // 2. Data Petugas Awal
        $petugasList = [
            'Ustadz Abdullah',
            'Ustadzah Fatimah',
            'Ustadz Hasan',
        ];

        $petugasModels = [];
        foreach ($petugasList as $nama) {
            $petugasModels[] = Petugas::updateOrCreate(
                ['nama' => $nama],
                ['is_active' => true]
            );
        }

        // 3. Data Siswa Awal
        $siswaList = [
            ['nama' => 'Ahmad Fauzi', 'kelas' => 'X-1', 'jurusan' => 'MIPA', 'tahun_ajaran' => '2026/2027'],
            ['nama' => 'Muhammad Rizky', 'kelas' => 'X-1', 'jurusan' => 'MIPA', 'tahun_ajaran' => '2026/2027'],
            ['nama' => 'Siti Aisyah', 'kelas' => 'X-2', 'jurusan' => 'IPS', 'tahun_ajaran' => '2026/2027'],
            ['nama' => 'Nur Hidayah', 'kelas' => 'X-2', 'jurusan' => 'IPS', 'tahun_ajaran' => '2026/2027'],
            ['nama' => 'Ali Ridho', 'kelas' => 'XI-1', 'jurusan' => 'Keagamaan', 'tahun_ajaran' => '2026/2027'],
            ['nama' => 'Zahra Amalia', 'kelas' => 'XI-1', 'jurusan' => 'MIPA', 'tahun_ajaran' => '2026/2027'],
            ['nama' => 'Fatimah Azzahra', 'kelas' => 'XI-2', 'jurusan' => 'Keagamaan', 'tahun_ajaran' => '2026/2027'],
            ['nama' => 'Yusuf Hakim', 'kelas' => 'XI-2', 'jurusan' => 'Bahasa', 'tahun_ajaran' => '2026/2027'],
            ['nama' => 'Khoirul Anam', 'kelas' => 'XII-1', 'jurusan' => 'MIPA', 'tahun_ajaran' => '2026/2027'],
            ['nama' => 'Aisyah Putri', 'kelas' => 'XII-1', 'jurusan' => 'IPS', 'tahun_ajaran' => '2026/2027'],
            ['nama' => 'Hamzah Maulana', 'kelas' => 'XII-2', 'jurusan' => 'Keagamaan', 'tahun_ajaran' => '2025/2026'],
            ['nama' => 'Salma Nabila', 'kelas' => 'XII-2', 'jurusan' => 'MIPA', 'tahun_ajaran' => '2025/2026'],
        ];

        $siswaModels = [];
        foreach ($siswaList as $item) {
            $siswaModels[] = Siswa::updateOrCreate(
                ['nama' => $item['nama'], 'kelas' => $item['kelas']],
                ['jurusan' => $item['jurusan'], 'tahun_ajaran' => $item['tahun_ajaran']]
            );
        }

        // 4. Data Contoh Setoran Awal (Ditandai jelas sebagai demonstrasi)
        $surahs = Quran::surahs();
        $sampleSurahIndices = [78, 67, 36, 55, 112, 18, 1, 114, 93, 87];

        for ($i = 0; $i < 15; $i++) {
            $siswa = $siswaModels[$i % count($siswaModels)];
            $petugas = $petugasModels[$i % count($petugasModels)];
            $surahIndex = $sampleSurahIndices[$i % count($sampleSurahIndices)] - 1;
            $kategori = ($i % 3 === 0) ? 'Murojaah' : 'Ziyadah';

            LaporanSetoran::create([
                'waktu_setoran' => now()->subHours($i * 12),
                'petugas_id' => $petugas->id,
                'nama_petugas' => $petugas->nama,
                'siswa_id' => $siswa->id,
                'nama_siswa' => $siswa->nama,
                'kelas' => $siswa->kelas,
                'jurusan' => $siswa->jurusan,
                'tahun_ajaran' => $siswa->tahun_ajaran,
                'kategori' => $kategori,
                'surah' => $surahs[$surahIndex],
                'ayat' => '1-'.(5 + ($i % 9) * 2),
                'nilai' => (string) (80 + ($i * 7) % 20),
                'catatan' => 'Data demonstrasi awal',
            ]);
        }
    }
}
