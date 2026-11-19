<?php

namespace App\Http\Controllers;

use App\Models\LaporanSetoran;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WaliSantriController extends Controller
{
    /**
     * Tampilkan halaman pencarian siswa dan pantau hafalan bagi wali murid (tanpa login).
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q', ''));
        $selectedKelas = trim((string) $request->input('kelas', ''));
        $siswaId = $request->input('siswa_id');
        $filterKategori = trim((string) $request->input('kategori', ''));
        $filterSurah = trim((string) $request->input('surah_filter', ''));

        $kelasList = Siswa::query()
            ->distinct()
            ->whereNotNull('kelas')
            ->orderBy('kelas')
            ->pluck('kelas');

        $selectedSiswa = null;
        $stats = null;
        $surahList = collect();
        $riwayatSetorans = collect();

        if (! empty($siswaId)) {
            $selectedSiswa = Siswa::with(['laporanSetorans' => function ($query) {
                $query->latest('waktu_setoran');
            }])->find($siswaId);

            if ($selectedSiswa) {
                $allSetorans = $selectedSiswa->laporanSetorans;
                $totalSetoran = $allSetorans->count();
                $averageNilai = $totalSetoran > 0 ? round((float) $allSetorans->avg('nilai'), 1) : 0.0;
                $averagePredikat = LaporanSetoran::predikat((int) round($averageNilai));
                $totalZiyadah = $allSetorans->where('kategori', 'Ziyadah')->count();
                $totalMurojaah = $allSetorans->where('kategori', 'Murojaah')->count();

                $surahList = $allSetorans->groupBy('surah')->map(function ($group) {
                    /** @var LaporanSetoran $latest */
                    $latest = $group->first();

                    return [
                        'surah' => $latest->surah,
                        'count' => $group->count(),
                        'max_nilai' => $group->max('nilai'),
                        'last_date' => $latest->waktu_setoran,
                    ];
                })->values();

                $stats = [
                    'total_setoran' => $totalSetoran,
                    'average_nilai' => $averageNilai,
                    'average_predikat' => $averagePredikat,
                    'total_ziyadah' => $totalZiyadah,
                    'total_murojaah' => $totalMurojaah,
                    'total_surah' => $surahList->count(),
                    'last_setoran' => $allSetorans->first(),
                ];

                $riwayatQuery = $selectedSiswa->laporanSetorans()->latest('waktu_setoran');

                if ($filterKategori !== '') {
                    $riwayatQuery->where('kategori', $filterKategori);
                }

                if ($filterSurah !== '') {
                    $riwayatQuery->where('surah', 'like', "%{$filterSurah}%");
                }

                $riwayatSetorans = $riwayatQuery->paginate(15)->withQueryString();
            }
        }

        $searchResults = collect();
        if ($selectedSiswa === null && ($search !== '' || $selectedKelas !== '')) {
            $searchQuery = Siswa::query()
                ->withCount('laporanSetorans')
                ->orderBy('nama');

            if ($search !== '') {
                $searchQuery->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('jurusan', 'like', "%{$search}%");
                });
            }

            if ($selectedKelas !== '') {
                $searchQuery->where('kelas', $selectedKelas);
            }

            $searchResults = $searchQuery->limit(24)->get();
        }

        return view('wali.index', [
            'kelasList' => $kelasList,
            'selectedKelas' => $selectedKelas,
            'search' => $search,
            'selectedSiswa' => $selectedSiswa,
            'stats' => $stats,
            'surahList' => $surahList,
            'riwayatSetorans' => $riwayatSetorans,
            'searchResults' => $searchResults,
            'filterKategori' => $filterKategori,
            'filterSurah' => $filterSurah,
        ]);
    }

    /**
     * Tautan langsung permalink siswa untuk orang tua.
     */
    public function show(Siswa $siswa, Request $request): RedirectResponse
    {
        $params = array_merge(['siswa_id' => $siswa->id], $request->query());

        return redirect()->route('wali.index', $params);
    }

    /**
     * Endpoint API pencarian siswa secara cepat (autocomplete / live search).
     */
    public function searchApi(Request $request): JsonResponse
    {
        $keyword = trim((string) $request->input('q', ''));
        $kelas = trim((string) $request->input('kelas', ''));

        if ($keyword === '' && $kelas === '') {
            return response()->json([]);
        }

        $query = Siswa::query()->withCount('laporanSetorans')->orderBy('nama');

        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                    ->orWhere('jurusan', 'like', "%{$keyword}%");
            });
        }

        if ($kelas !== '') {
            $query->where('kelas', $kelas);
        }

        $students = $query->limit(10)->get()->map(function (Siswa $s) {
            $detailKelas = $s->kelas.($s->jurusan ? ' '.$s->jurusan : '');

            return [
                'id' => $s->id,
                'nama' => $s->nama,
                'kelas' => $s->kelas,
                'jurusan' => $s->jurusan,
                'detail_kelas' => $detailKelas,
                'tahun_ajaran' => $s->tahun_ajaran,
                'total_setoran' => $s->laporan_setorans_count,
                'url' => route('wali.index', ['siswa_id' => $s->id]),
            ];
        });

        return response()->json($students);
    }
}
