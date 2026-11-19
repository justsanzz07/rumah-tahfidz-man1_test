<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePetugasRequest;
use App\Models\LaporanSetoran;
use App\Models\Petugas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PetugasController extends Controller
{
    /**
     * Tampilkan daftar petugas tahfidz dalam format kartu terstruktur.
     */
    public function index(): View
    {
        $petugasList = Petugas::query()
            ->withCount('laporanSetorans')
            ->withAvg('laporanSetorans', 'nilai')
            ->orderByDesc('is_active')
            ->orderBy('nama')
            ->get();

        // Hitung jumlah santri unik yang pernah disimak oleh masing-masing petugas
        $santriCounts = LaporanSetoran::query()
            ->whereNotNull('petugas_id')
            ->select('petugas_id', DB::raw('count(distinct coalesce(siswa_id, nama_siswa)) as total_santri'))
            ->groupBy('petugas_id')
            ->pluck('total_santri', 'petugas_id');

        // Ambil setoran terakhir per petugas untuk highlight di kartu
        $latestSetorans = LaporanSetoran::query()
            ->whereNotNull('petugas_id')
            ->latest('waktu_setoran')
            ->get()
            ->groupBy('petugas_id');

        foreach ($petugasList as $petugas) {
            $petugas->total_santri = (int) $santriCounts->get($petugas->id, 0);
            $petugas->latest_setoran = $latestSetorans->get($petugas->id)?->first();
        }

        $totalPetugas = $petugasList->count();
        $totalAktif = $petugasList->where('is_active', true)->count();
        $totalSetoranSemua = (int) $petugasList->sum('laporan_setorans_count');

        return view('admin.petugas', [
            'petugasList' => $petugasList,
            'totalPetugas' => $totalPetugas,
            'totalAktif' => $totalAktif,
            'totalNonaktif' => $totalPetugas - $totalAktif,
            'totalSetoranSemua' => $totalSetoranSemua,
        ]);
    }

    /**
     * Tambah petugas baru.
     */
    public function store(StorePetugasRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $petugas = Petugas::create([
            'nama' => $validated['nama'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.petugas.index')
            ->with('success', "Petugas {$petugas->nama} berhasil ditambahkan.");
    }

    /**
     * Perbarui data nama dan status aktif petugas.
     */
    public function update(StorePetugasRequest $request, Petugas $petugas): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $updateData = [
            'nama' => $validated['nama'],
        ];

        if ($request->has('is_active')) {
            $updateData['is_active'] = $request->boolean('is_active');
        }

        $petugas->update($updateData);

        $statusText = $petugas->is_active ? 'Aktif' : 'Nonaktif';
        $message = "Data ustadz/petugas {$petugas->nama} berhasil diperbarui (Status: {$statusText}).";

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $petugas,
            ]);
        }

        return redirect()->route('admin.petugas.index')->with('success', $message);
    }

    /**
     * Hapus data petugas.
     */
    public function destroy(Petugas $petugas): RedirectResponse|JsonResponse
    {
        $nama = $petugas->nama;
        $petugas->delete();

        $message = "Petugas {$nama} berhasil dihapus dari sistem.";

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->route('admin.petugas.index')->with('success', $message);
    }

    /**
     * Ambil riwayat penyimakan setoran oleh petugas tertentu.
     */
    public function riwayat(Request $request, Petugas $petugas): JsonResponse|View
    {
        $query = $petugas->laporanSetorans()->latest('waktu_setoran');

        $laporanList = $query->paginate(20)->withQueryString();

        $totalSetoran = $petugas->laporanSetorans()->count();
        $totalSantri = $petugas->laporanSetorans()->distinct('siswa_id')->count('siswa_id');
        $avgNilai = round((float) $petugas->laporanSetorans()->avg('nilai'), 1);

        if ($request->expectsJson() || $request->ajax()) {
            $formattedData = $laporanList->getCollection()->map(function (LaporanSetoran $item) {
                $pred = $item->predikat;

                return [
                    'id' => $item->id,
                    'waktu_setoran' => $item->waktu_setoran->format('d/m/Y H:i'),
                    'nama_siswa' => $item->nama_siswa,
                    'kelas' => $item->kelas,
                    'jurusan' => $item->jurusan ?? '-',
                    'surah' => $item->surah,
                    'ayat' => $item->ayat,
                    'kategori' => $item->kategori,
                    'nilai' => $item->nilai,
                    'predikat_label' => $pred['label'],
                    'predikat_class' => $pred['class'],
                    'catatan' => $item->catatan ?? '-',
                ];
            });

            return response()->json([
                'success' => true,
                'petugas' => [
                    'id' => $petugas->id,
                    'nama' => $petugas->nama,
                    'is_active' => $petugas->is_active,
                    'total_setoran' => $totalSetoran,
                    'total_santri' => $totalSantri,
                    'avg_nilai' => $avgNilai,
                ],
                'data' => $formattedData,
                'pagination' => [
                    'total' => $laporanList->total(),
                    'current_page' => $laporanList->currentPage(),
                    'last_page' => $laporanList->lastPage(),
                ],
            ]);
        }

        return view('admin.petugas-riwayat', [
            'petugas' => $petugas,
            'laporanList' => $laporanList,
            'totalSetoran' => $totalSetoran,
            'totalSantri' => $totalSantri,
            'avgNilai' => $avgNilai,
        ]);
    }
}
