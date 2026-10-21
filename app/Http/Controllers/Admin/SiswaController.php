<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSiswaRequest;
use App\Http\Requests\UpdateSiswaRequest;
use App\Models\Siswa;
use App\Support\Quran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SiswaController extends Controller
{
    /**
     * Tampilkan daftar siswa dengan filter dan pencarian.
     */
    public function index(Request $request): View
    {
        $filterTahun = trim((string) $request->input('tahun', ''));
        $filterKelas = trim((string) $request->input('kelas', ''));
        $filterJurusan = trim((string) $request->input('jurusan', ''));
        $search = trim((string) $request->input('q', ''));

        $query = Siswa::query()->orderBy('kelas')->orderBy('nama');

        if ($filterTahun !== '') {
            $query->where('tahun_ajaran', $filterTahun);
        }

        if ($filterKelas !== '') {
            $query->where('kelas', $filterKelas);
        }

        if ($filterJurusan !== '') {
            $query->where('jurusan', $filterJurusan);
        }

        if ($search !== '') {
            $query->where('nama', 'like', "%{$search}%");
        }

        $siswaList = $query->paginate(30)->withQueryString();

        $tahunOptions = Siswa::query()
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

        return view('admin.siswa', [
            'siswaList' => $siswaList,
            'totalSiswa' => Siswa::count(),
            'tahunOptions' => $tahunOptions,
            'kelasOptions' => $kelasOptions,
            'jurusanOptions' => Quran::majors(),
            'filterTahun' => $filterTahun,
            'filterKelas' => $filterKelas,
            'filterJurusan' => $filterJurusan,
            'search' => $search,
            'defaultTahun' => $tahunOptions->first() ?? '2026/2027',
        ]);
    }

    /**
     * Tambah data siswa baru secara manual.
     */
    public function store(StoreSiswaRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $siswa = Siswa::create($validated);

        return redirect()->route('admin.siswa.index')
            ->with('success', "Siswa {$siswa->nama} berhasil ditambahkan.");
    }

    /**
     * Perbarui data siswa.
     */
    public function update(UpdateSiswaRequest $request, Siswa $siswa): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $siswa->update($validated);

        $message = "Data siswa {$siswa->nama} berhasil diperbarui.";

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $siswa,
            ]);
        }

        return redirect()->route('admin.siswa.index')->with('success', $message);
    }

    /**
     * Hapus data siswa.
     */
    public function destroy(Siswa $siswa): RedirectResponse|JsonResponse
    {
        $nama = $siswa->nama;
        $siswa->delete();

        $message = "Siswa {$nama} berhasil dihapus. Riwayat setoran lampau tetap diarsipkan.";

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->route('admin.siswa.index')->with('success', $message);
    }

    /**
     * Impor data siswa melalui berkas CSV atau tempelan teks.
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file_csv' => ['nullable', 'file', 'mimes:csv,txt', 'max:2048'],
            'raw_text' => ['nullable', 'string', 'max:50000'],
            'default_tahun' => ['nullable', 'string', 'max:20'],
        ]);

        $defaultTahun = trim((string) $request->input('default_tahun', '2026/2027'));
        if ($defaultTahun === '') {
            $defaultTahun = '2026/2027';
        }

        $lines = [];

        // 1. Jika pengguna mengunggah berkas CSV
        if ($request->hasFile('file_csv')) {
            $path = $request->file('file_csv')->getRealPath();
            $fileContent = file_get_contents($path);
            if ($fileContent !== false) {
                // Bersihkan BOM jika ada
                $fileContent = preg_replace('/^\xEF\xBB\xBF/', '', $fileContent);
                $lines = preg_split('/\r\n|\r|\n/', $fileContent);
            }
        } elseif ($request->filled('raw_text')) {
            // 2. Jika pengguna menempelkan teks
            $lines = preg_split('/\r\n|\r|\n/', (string) $request->input('raw_text'));
        }

        if (empty($lines)) {
            return redirect()->back()->withErrors(['import' => 'Harap unggah berkas CSV atau tempelkan data teks siswa.']);
        }

        $importedCount = 0;
        $validMajors = Quran::majors();

        foreach ($lines as $index => $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            // Parse kolom dengan mempertimbangkan koma, titik koma, atau tab
            $delimiter = str_contains($line, ';') ? ';' : (str_contains($line, "\t") ? "\t" : ',');
            $cols = str_getcsv($line, $delimiter);
            $cols = array_map('trim', $cols);

            $nama = $cols[0] ?? '';
            // Lewati baris header
            if ($index === 0 && preg_match('/^(nama|name|siswa)/i', $nama)) {
                continue;
            }

            if ($nama === '') {
                continue;
            }

            $kelas = $cols[1] ?? '';
            if ($kelas === '') {
                continue;
            }

            $rawJurusan = $cols[2] ?? '';
            $jurusan = in_array(strtoupper($rawJurusan), array_map('strtoupper', $validMajors), true)
                ? strtoupper($rawJurusan)
                : null;

            $tahun = ! empty($cols[3]) ? $cols[3] : $defaultTahun;

            Siswa::updateOrCreate(
                ['nama' => $nama, 'kelas' => $kelas],
                [
                    'jurusan' => $jurusan,
                    'tahun_ajaran' => $tahun,
                ]
            );

            $importedCount++;
        }

        if ($importedCount === 0) {
            return redirect()->back()->withErrors(['import' => 'Tidak ada baris siswa yang valid untuk diimpor. Format kolom: nama, kelas, jurusan, tahun_ajaran.']);
        }

        return redirect()->route('admin.siswa.index')
            ->with('success', "Sebanyak {$importedCount} data siswa berhasil diimpor.");
    }

    /**
     * Unduh templat berkas CSV untuk mempermudah impor siswa.
     */
    public function downloadTemplate(): StreamedResponse
    {
        $fileName = 'templat-impor-siswa-tahfidz.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['nama', 'kelas', 'jurusan', 'tahun_ajaran']);
            fputcsv($handle, ['Ahmad Fauzi', 'X-1', 'MIPA', '2026/2027']);
            fputcsv($handle, ['Siti Aisyah', 'XI-2', 'IPS', '2026/2027']);
            fputcsv($handle, ['Ali Ridho', 'XI-1', 'Keagamaan', '2026/2027']);
            fputcsv($handle, ['Yusuf Hakim', 'XII-1', 'Bahasa', '2026/2027']);

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }
}
