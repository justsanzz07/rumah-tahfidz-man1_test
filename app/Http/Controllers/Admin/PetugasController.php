<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePetugasRequest;
use App\Models\Petugas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PetugasController extends Controller
{
    /**
     * Tampilkan daftar petugas tahfidz.
     */
    public function index(): View
    {
        $petugasList = Petugas::query()
            ->withCount('laporanSetorans')
            ->orderBy('nama')
            ->get();

        return view('admin.petugas', [
            'petugasList' => $petugasList,
            'totalPetugas' => $petugasList->count(),
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
     * Perbarui data nama petugas.
     */
    public function update(StorePetugasRequest $request, Petugas $petugas): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $petugas->update([
            'nama' => $validated['nama'],
        ]);

        $message = "Nama petugas berhasil diperbarui menjadi {$petugas->nama}.";

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

        $message = "Petugas {$nama} berhasil dihapus.";

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->route('admin.petugas.index')->with('success', $message);
    }
}
