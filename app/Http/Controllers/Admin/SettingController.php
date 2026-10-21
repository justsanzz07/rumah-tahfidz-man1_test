<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Tampilkan halaman pengaturan admin (ganti logo & ganti sandi).
     */
    public function index(): View
    {
        $currentLogo = Setting::get('app_logo');
        $adminUser = Auth::user();

        return view('admin.pengaturan', [
            'currentLogo' => $currentLogo,
            'adminUser' => $adminUser,
        ]);
    }

    /**
     * Unggah dan perbarui logo aplikasi.
     */
    public function updateLogo(Request $request): RedirectResponse
    {
        $request->validate([
            'logo' => ['required', 'image', 'mimes:png,jpg,jpeg,webp,svg', 'max:2048'],
        ], [
            'logo.required' => 'Pilih berkas foto logo terlebih dahulu.',
            'logo.image' => 'Berkas harus berupa gambar.',
            'logo.mimes' => 'Format foto harus berupa PNG, JPG, JPEG, WEBP, atau SVG.',
            'logo.max' => 'Ukuran berkas logo maksimal 2 MB.',
        ]);

        $file = $request->file('logo');
        $uploadDir = public_path('uploads');

        if (! File::isDirectory($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true, true);
        }

        // Hapus logo lama jika ada
        $oldLogo = Setting::get('app_logo');
        if ($oldLogo && File::exists(public_path($oldLogo))) {
            File::delete(public_path($oldLogo));
        }

        $filename = 'logo_'.time().'.'.$file->getClientOriginalExtension();
        $file->move($uploadDir, $filename);

        Setting::set('app_logo', 'uploads/'.$filename);

        return redirect()->route('admin.setting.index')
            ->with('success', 'Logo aplikasi berhasil diperbarui.');
    }

    /**
     * Kembalikan logo aplikasi ke ikon default.
     */
    public function resetLogo(): RedirectResponse
    {
        $oldLogo = Setting::get('app_logo');
        if ($oldLogo && File::exists(public_path($oldLogo))) {
            File::delete(public_path($oldLogo));
        }

        Setting::set('app_logo', null);

        return redirect()->route('admin.setting.index')
            ->with('success', 'Logo aplikasi berhasil dikembalikan ke ikon default.');
    }

    /**
     * Perbarui kata sandi akun administrator.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'password_lama' => ['required', 'string'],
            'password_baru' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'password_lama.required' => 'Kata sandi saat ini wajib diisi.',
            'password_baru.required' => 'Kata sandi baru wajib diisi.',
            'password_baru.min' => 'Kata sandi baru minimal terdiri dari :min karakter.',
            'password_baru.confirmed' => 'Konfirmasi kata sandi baru tidak sesuai.',
        ]);

        /** @var User $admin */
        $admin = Auth::user();

        if (! Hash::check($request->input('password_lama'), $admin->password)) {
            return redirect()->back()->withErrors([
                'password_lama' => 'Kata sandi saat ini yang Anda masukkan salah.',
            ]);
        }

        $admin->update([
            'password' => Hash::make($request->input('password_baru')),
        ]);

        return redirect()->route('admin.setting.index')
            ->with('success', 'Kata sandi administrator berhasil diperbarui.');
    }
}
