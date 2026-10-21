<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanSetoran;
use App\Models\Siswa;
use App\Support\Quran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    /**
     * Tampilkan rekapitulasi seluruh laporan setoran dengan filter dan statistik.
     */
    public function index(Request $request): View
    {
        $filterTahun = trim((string) $request->input('tahun', ''));
        $filterKelas = trim((string) $request->input('kelas', ''));
        $filterKategori = trim((string) $request->input('kategori', ''));
        $search = trim((string) $request->input('q', ''));

        $query = LaporanSetoran::query()->latest('waktu_setoran');

        if ($filterTahun !== '') {
            $query->where('tahun_ajaran', $filterTahun);
        }

        if ($filterKelas !== '') {
            $query->where('kelas', $filterKelas);
        }

        if ($filterKategori !== '') {
            $query->where('kategori', $filterKategori);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                    ->orWhere('nama_petugas', 'like', "%{$search}%")
                    ->orWhere('surah', 'like', "%{$search}%")
                    ->orWhere('nilai', 'like', "%{$search}%");
            });
        }

        $laporanList = $query->paginate(25)->withQueryString();

        // Hitung statistik berdasarkan filter aktif
        $statsQuery = clone $query;
        // Unset pagination limit
        $allMatching = $statsQuery->get();
        $totalLaporan = $allMatching->count();
        $totalZiyadah = $allMatching->where('kategori', 'Ziyadah')->count();
        $totalMurojaah = $allMatching->where('kategori', 'Murojaah')->count();

        // Opsi filter
        $tahunOptions = LaporanSetoran::query()
            ->distinct()
            ->whereNotNull('tahun_ajaran')
            ->orderByDesc('tahun_ajaran')
            ->pluck('tahun_ajaran');

        if ($tahunOptions->isEmpty()) {
            $tahunOptions = collect(['2026/2027']);
        }

        $kelasOptions = Siswa::query()
            ->distinct()
            ->whereNotNull('kelas')
            ->orderBy('kelas')
            ->pluck('kelas');

        $demoCount = LaporanSetoran::query()
            ->where('catatan', 'like', '%demonstrasi%')
            ->count();

        return view('admin.dashboard', [
            'laporanList' => $laporanList,
            'totalLaporan' => $totalLaporan,
            'totalZiyadah' => $totalZiyadah,
            'totalMurojaah' => $totalMurojaah,
            'demoCount' => $demoCount,
            'tahunOptions' => $tahunOptions,
            'kelasOptions' => $kelasOptions,
            'kategoriOptions' => Quran::categories(),
            'filterTahun' => $filterTahun,
            'filterKelas' => $filterKelas,
            'filterKategori' => $filterKategori,
            'search' => $search,
        ]);
    }

    /**
     * Hapus laporan setoran dengan konfirmasi.
     */
    public function destroy(LaporanSetoran $laporan): RedirectResponse|JsonResponse
    {
        $id = $laporan->id;
        $laporan->delete();

        $message = "Laporan setoran nomor #{$id} berhasil dihapus.";

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Bersihkan data demonstrasi awal.
     */
    public function clearDemo(): RedirectResponse
    {
        $count = LaporanSetoran::query()
            ->where('catatan', 'like', '%demonstrasi%')
            ->delete();

        return redirect()->back()->with('success', "Sebanyak {$count} data demonstrasi awal berhasil dibersihkan.");
    }

    /**
     * Ekspor laporan ke file CSV yang kompatibel penuh dengan Microsoft Excel.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $filterTahun = trim((string) $request->input('tahun', ''));
        $filterKelas = trim((string) $request->input('kelas', ''));
        $filterKategori = trim((string) $request->input('kategori', ''));
        $search = trim((string) $request->input('q', ''));

        $query = LaporanSetoran::query()->latest('waktu_setoran');

        if ($filterTahun !== '') {
            $query->where('tahun_ajaran', $filterTahun);
        }

        if ($filterKelas !== '') {
            $query->where('kelas', $filterKelas);
        }

        if ($filterKategori !== '') {
            $query->where('kategori', $filterKategori);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                    ->orWhere('nama_petugas', 'like', "%{$search}%")
                    ->orWhere('surah', 'like', "%{$search}%")
                    ->orWhere('nilai', 'like', "%{$search}%");
            });
        }

        $records = $query->get();
        $fileName = 'rekapitulasi-setoran-tahfidz-'.date('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($records) {
            $handle = fopen('php://output', 'w');

            // Tambahkan UTF-8 BOM agar terbaca sempurna di Microsoft Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header kolom
            fputcsv($handle, [
                'Waktu Setoran',
                'Nama Siswa',
                'Kelas',
                'Jurusan',
                'Tahun Ajaran',
                'Nama Petugas',
                'Kategori',
                'Surah',
                'Jumlah Ayat',
                'Nilai',
                'Catatan',
            ]);

            foreach ($records as $r) {
                fputcsv($handle, [
                    $r->waktu_setoran->format('d/m/Y H:i'),
                    $r->nama_siswa,
                    $r->kelas,
                    $r->jurusan ?? '',
                    $r->tahun_ajaran,
                    $r->nama_petugas,
                    $r->kategori,
                    $r->surah,
                    $r->ayat,
                    $r->nilai,
                    $r->catatan ?? '',
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }
}
