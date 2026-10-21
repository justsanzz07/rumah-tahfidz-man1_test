<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSetoranRequest;
use App\Models\LaporanSetoran;
use App\Models\Petugas;
use App\Models\Siswa;
use App\Support\Quran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SetoranController extends Controller
{
    /**
     * Tampilkan halaman utama pencatatan setoran bagi petugas.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q', ''));
        $selectedKelas = trim((string) $request->input('kelas', ''));

        $petugasList = Petugas::query()
            ->where('is_active', true)
            ->orderBy('nama')
            ->get();

        $kelasList = Siswa::query()
            ->distinct()
            ->whereNotNull('kelas')
            ->orderBy('kelas')
            ->pluck('kelas');

        $siswaQuery = Siswa::query()->orderBy('kelas')->orderBy('nama');
        if ($selectedKelas !== '') {
            $siswaQuery->where('kelas', $selectedKelas);
        }
        $siswaList = $siswaQuery->get();

        $riwayatQuery = LaporanSetoran::query()->latest('waktu_setoran');

        if ($search !== '') {
            $riwayatQuery->where(function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                    ->orWhere('nama_petugas', 'like', "%{$search}%")
                    ->orWhere('surah', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%")
                    ->orWhere('nilai', 'like', "%{$search}%");
            });
        }

        $riwayatList = $riwayatQuery->limit(50)->get();
        $totalLaporan = LaporanSetoran::count();

        return view('petugas.index', [
            'petugasList' => $petugasList,
            'kelasList' => $kelasList,
            'siswaList' => $siswaList,
            'kategoriList' => Quran::categories(),
            'surahList' => Quran::surahs(),
            'riwayatList' => $riwayatList,
            'totalLaporan' => $totalLaporan,
            'search' => $search,
            'selectedKelas' => $selectedKelas,
        ]);
    }

    /**
     * Simpan laporan setoran hafalan baru ke database.
     */
    public function store(StoreSetoranRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        $petugas = Petugas::findOrFail($validated['petugas_id']);
        $siswa = Siswa::findOrFail($validated['siswa_id']);

        $laporan = LaporanSetoran::create([
            'waktu_setoran' => $validated['waktu_setoran'],
            'petugas_id' => $petugas->id,
            'nama_petugas' => $petugas->nama,
            'siswa_id' => $siswa->id,
            'nama_siswa' => $siswa->nama,
            'kelas' => $siswa->kelas,
            'jurusan' => $siswa->jurusan,
            'tahun_ajaran' => $siswa->tahun_ajaran,
            'kategori' => $validated['kategori'],
            'surah' => $validated['surah'],
            'ayat' => $validated['ayat'],
            'nilai' => $validated['nilai'],
            'catatan' => $validated['catatan'] ?? null,
        ]);

        $message = 'Laporan setoran untuk '.$siswa->nama.' berhasil disimpan.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'id' => $laporan->id,
                    'waktu' => $laporan->waktu_setoran->isoFormat('D MMMM Y, HH:mm'),
                    'nama_siswa' => $laporan->nama_siswa,
                    'kelas' => $laporan->kelas,
                    'jurusan' => $laporan->jurusan,
                    'nama_petugas' => $laporan->nama_petugas,
                    'kategori' => $laporan->kategori,
                    'surah' => $laporan->surah,
                    'ayat' => $laporan->ayat,
                    'nilai' => $laporan->nilai,
                ],
            ]);
        }

        return redirect()->route('petugas.index')->with('success', $message);
    }

    /**
     * Ambil data siswa berdasarkan kelas secara dinamis (JSON API).
     */
    public function getSiswa(Request $request): JsonResponse
    {
        $kelas = trim((string) $request->input('kelas', ''));

        $query = Siswa::query()->orderBy('nama');
        if ($kelas !== '') {
            $query->where('kelas', $kelas);
        }

        $data = $query->get()->map(function (Siswa $s) {
            $detail = $s->kelas.($s->jurusan ? ' '.$s->jurusan : '');

            return [
                'id' => $s->id,
                'nama' => $s->nama,
                'kelas' => $s->kelas,
                'jurusan' => $s->jurusan,
                'label' => $s->nama.' ('.$detail.')',
            ];
        });

        return response()->json($data);
    }
}
