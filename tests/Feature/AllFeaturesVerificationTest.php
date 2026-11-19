<?php

namespace Tests\Feature;

use App\Models\LaporanSetoran;
use App\Models\Petugas;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\User;
use App\Support\Quran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AllFeaturesVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Petugas $petugas;

    protected Siswa $siswa;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@man1probolinggo.sch.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        $this->petugas = Petugas::create([
            'nama' => 'Ustadz Ahmad Fauzan',
            'is_active' => true,
        ]);

        $this->siswa = Siswa::create([
            'nama' => 'Muhammad Rayhan',
            'kelas' => 'X-1',
            'jurusan' => 'MIPA',
            'tahun_ajaran' => '2026/2027',
        ]);
    }

    /** 1. Halaman Utama Petugas & Digital Mushaf */
    public function test_halaman_utama_petugas_loads_with_digital_mushaf_and_live_clock(): void
    {
        $response = $this->get(route('petugas.index'));

        $response->assertStatus(200);
        $response->assertSee('Laporan Rumah Tahfidz');
        $response->assertSee('MAN 1 PROBOLINGGO');
        $response->assertSee('Formulir Catat Setoran');
        $response->assertSee('Waktu Setoran');
        $response->assertSee('live-header-time');
        $response->assertSee('Buka Mushaf');

        // Memastikan tombol Panel Admin dan Keluar TIDAK ada di header halaman petugas
        $response->assertDontSee('Masuk ke Panel Administrator');
        $response->assertDontSee('Keluar dari sesi administrator');
    }

    /** 2. API Siswa Berdasarkan Kelas */
    public function test_api_siswa_by_kelas_returns_correct_json(): void
    {
        Siswa::create([
            'nama' => 'Fatimah Az-Zahra',
            'kelas' => 'X-2',
            'jurusan' => 'Keagamaan',
            'tahun_ajaran' => '2026/2027',
        ]);

        $response = $this->getJson(route('petugas.siswa-by-kelas', ['kelas' => 'X-1']));

        $response->assertStatus(200);
        $response->assertJsonFragment(['nama' => 'Muhammad Rayhan']);
        $response->assertJsonMissing(['nama' => 'Fatimah Az-Zahra']);
    }

    /** 3. Simpan Setoran Hafalan via Form & via AJAX */
    public function test_simpan_setoran_hafalan_berhasil_tersimpan_di_database(): void
    {
        $payload = [
            'waktu_setoran' => now()->format('Y-m-d H:i'),
            'petugas_id' => $this->petugas->id,
            'siswa_id' => $this->siswa->id,
            'kategori' => 'Ziyadah',
            'surah' => '1. Al-Fatihah',
            'ayat' => '1-7',
            'nilai' => '98',
            'catatan' => 'Makhraj huruf fasih dan lancar',
        ];

        // 3a. Via POST Web
        $response = $this->post(route('petugas.store'), $payload);
        $response->assertRedirect(route('petugas.index'));
        $this->assertDatabaseHas('laporan_setorans', [
            'nama_siswa' => 'Muhammad Rayhan',
            'nama_petugas' => 'Ustadz Ahmad Fauzan',
            'kategori' => 'Ziyadah',
            'surah' => '1. Al-Fatihah',
            'ayat' => '1-7',
            'nilai' => '98',
        ]);

        // 3b. Via POST AJAX JSON
        $ajaxPayload = array_merge($payload, [
            'kategori' => 'Murojaah',
            'surah' => '78. An-Naba',
            'ayat' => '1-40',
            'nilai' => '92',
        ]);

        $ajaxResponse = $this->postJson(route('petugas.store'), $ajaxPayload);
        $ajaxResponse->assertStatus(200);
        $ajaxResponse->assertJson(['success' => true]);
        $this->assertDatabaseHas('laporan_setorans', [
            'kategori' => 'Murojaah',
            'surah' => '78. An-Naba',
            'ayat' => '1-40',
            'nilai' => '92',
        ]);
    }

    /** 4. Autentikasi Admin (Email & Password, Proteksi & Logout) */
    public function test_autentikasi_admin_berfungsi_dengan_email_dan_password_serta_logout(): void
    {
        // 4a. Halaman admin dilindungi middleware
        $unauth = $this->get(route('admin.dashboard'));
        $unauth->assertRedirect(route('login'));

        // 4b. Halaman login tampil
        $loginPage = $this->get(route('login'));
        $loginPage->assertStatus(200);
        $loginPage->assertSee('Masuk ke Panel Administrator');
        $loginPage->assertDontSee('PIN Cepat');

        // 4c. Login kosong ditolak validasi
        $emptyLogin = $this->post(route('login.post'), []);
        $emptyLogin->assertSessionHasErrors(['email', 'password']);

        // 4d. Login kredensial salah ditolak
        $wrongLogin = $this->post(route('login.post'), [
            'email' => 'admin@man1probolinggo.sch.id',
            'password' => 'salah123',
        ]);
        $wrongLogin->assertSessionHasErrors('auth');

        // 4e. Login sukses via Email & Password
        $loginEmail = $this->post(route('login.post'), [
            'email' => 'admin@man1probolinggo.sch.id',
            'password' => 'admin123',
        ]);
        $loginEmail->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->admin);

        // 4f. Logout sukses
        $logoutResponse = $this->post(route('logout'));
        $logoutResponse->assertRedirect();
        $this->assertGuest();
    }

    /** 5. Dashboard Rekapitulasi Laporan, Filter, & Ekspor CSV */
    public function test_dashboard_rekapitulasi_filter_dan_ekspor_csv(): void
    {
        LaporanSetoran::create([
            'waktu_setoran' => now(),
            'petugas_id' => $this->petugas->id,
            'nama_petugas' => $this->petugas->nama,
            'siswa_id' => $this->siswa->id,
            'nama_siswa' => $this->siswa->nama,
            'kelas' => $this->siswa->kelas,
            'tahun_ajaran' => $this->siswa->tahun_ajaran,
            'kategori' => 'Ziyadah',
            'surah' => '2. Al-Baqarah',
            'ayat' => '1-5',
            'nilai' => '90',
        ]);

        // 5a. Akses dashboard admin
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Tabel Rekapitulasi Laporan Setoran');
        $response->assertSee('Muhammad Rayhan');
        $response->assertSee('Al-Baqarah');

        // Memastikan di header halaman admin TIDAK ada tombol Halaman Pendamping dan Keluar
        $response->assertDontSee('<span class="hamburger-label">Menu</span>', false);

        // 5b. Ekspor CSV
        $export = $this->actingAs($this->admin)->get(route('admin.laporan.export'));
        $export->assertStatus(200);
        $export->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('Muhammad Rayhan', $export->streamedContent());
    }

    /** 6. Manajemen Data Siswa (CRUD, Template, Impor File & Text) */
    public function test_manajemen_siswa_lengkap_crud_template_dan_impor(): void
    {
        // 6a. Tambah Siswa Manual
        $create = $this->actingAs($this->admin)->post(route('admin.siswa.store'), [
            'nama' => 'Ali bin Abi Thalib',
            'kelas' => 'XII-1',
            'jurusan' => 'Keagamaan',
            'tahun_ajaran' => '2026/2027',
        ]);
        $create->assertRedirect(route('admin.siswa.index'));
        $this->assertDatabaseHas('siswas', ['nama' => 'Ali bin Abi Thalib']);

        $ali = Siswa::where('nama', 'Ali bin Abi Thalib')->firstOrFail();

        // 6b. Update Siswa
        $update = $this->actingAs($this->admin)->put(route('admin.siswa.update', $ali), [
            'nama' => 'Ali bin Abi Thalib Karramallahu Wajhah',
            'kelas' => 'XII-1',
            'jurusan' => 'Keagamaan',
            'tahun_ajaran' => '2026/2027',
        ]);
        $update->assertRedirect(route('admin.siswa.index'));
        $this->assertDatabaseHas('siswas', ['nama' => 'Ali bin Abi Thalib Karramallahu Wajhah']);

        // 6c. Unduh Template CSV Siswa
        $template = $this->actingAs($this->admin)->get(route('admin.siswa.template'));
        $template->assertStatus(200);
        $template->assertHeader('content-type', 'text/csv; charset=UTF-8');

        // 6d. Impor Siswa dari File CSV Upload
        $csvContent = "Nama,Kelas,Jurusan,Tahun Ajaran\nUmar bin Khattab,XI-2,IPS,2026/2027\nAbu Bakar Ash-Shiddiq,X-3,MIPA,2026/2027";
        $file = UploadedFile::fake()->createWithContent('daftar_siswa.csv', $csvContent);

        $importFile = $this->actingAs($this->admin)->post(route('admin.siswa.import'), [
            'file_csv' => $file,
        ]);
        $importFile->assertRedirect(route('admin.siswa.index'));
        $this->assertDatabaseHas('siswas', ['nama' => 'Umar bin Khattab']);
        $this->assertDatabaseHas('siswas', ['nama' => 'Abu Bakar Ash-Shiddiq']);

        // 6e. Hapus Siswa
        $delete = $this->actingAs($this->admin)->delete(route('admin.siswa.destroy', $ali));
        $delete->assertRedirect(route('admin.siswa.index'));
        $this->assertDatabaseMissing('siswas', ['id' => $ali->id]);
    }

    /** 7. Manajemen Petugas Penyimak (CRUD) */
    public function test_manajemen_petugas_penyimak_lengkap_crud(): void
    {
        // 7a. Tambah Petugas
        $create = $this->actingAs($this->admin)->post(route('admin.petugas.store'), [
            'nama' => 'Ustadzah Aisyah Humaira',
        ]);
        $create->assertRedirect(route('admin.petugas.index'));
        $this->assertDatabaseHas('petugas', ['nama' => 'Ustadzah Aisyah Humaira']);

        $petugasBaru = Petugas::where('nama', 'Ustadzah Aisyah Humaira')->firstOrFail();

        // 7b. Index Petugas dengan Petugas Baru (belum memiliki riwayat setoran)
        $indexPage = $this->actingAs($this->admin)->get(route('admin.petugas.index'));
        $indexPage->assertStatus(200);
        $indexPage->assertSee('Ustadzah Aisyah Humaira');
        $indexPage->assertSee('Belum ada catatan setoran');

        // 7c. Update Petugas
        $update = $this->actingAs($this->admin)->put(route('admin.petugas.update', $petugasBaru), [
            'nama' => 'Ustadzah Aisyah Humaira, S.Pd.I',
        ]);
        $update->assertRedirect(route('admin.petugas.index'));
        $this->assertDatabaseHas('petugas', ['nama' => 'Ustadzah Aisyah Humaira, S.Pd.I']);

        // 7d. Hapus Petugas
        $delete = $this->actingAs($this->admin)->delete(route('admin.petugas.destroy', $petugasBaru));
        $delete->assertRedirect(route('admin.petugas.index'));
        $this->assertDatabaseMissing('petugas', ['id' => $petugasBaru->id]);
    }

    /** 7b. Fitur Riwayat Penyimakan Petugas & Toggle Status Aktif */
    public function test_petugas_riwayat_penyimakan_html_dan_ajax(): void
    {
        // Update status aktif ke false
        $updateStatus = $this->actingAs($this->admin)->put(route('admin.petugas.update', $this->petugas), [
            'nama' => $this->petugas->nama,
            'is_active' => '0',
        ]);
        $updateStatus->assertRedirect(route('admin.petugas.index'));
        $this->petugas->refresh();
        $this->assertFalse($this->petugas->is_active);

        // Akses halaman penuh riwayat petugas (HTML)
        $riwayatView = $this->actingAs($this->admin)->get(route('admin.petugas.riwayat', $this->petugas));
        $riwayatView->assertStatus(200);
        $riwayatView->assertSee('Riwayat Penyimakan');
        $riwayatView->assertSee($this->petugas->nama);

        // Akses riwayat petugas via AJAX JSON
        $riwayatJson = $this->actingAs($this->admin)->getJson(route('admin.petugas.riwayat', $this->petugas));
        $riwayatJson->assertStatus(200);
        $riwayatJson->assertJsonStructure([
            'success',
            'petugas' => ['id', 'nama', 'is_active', 'total_setoran', 'total_santri', 'avg_nilai'],
            'data',
            'pagination',
        ]);
    }

    /** 8. Pengaturan Logo, Ganti Password, & Tombol Sesi Admin */
    public function test_pengaturan_aplikasi_logo_password_dan_tombol_sesi(): void
    {
        // 8a. Akses Pengaturan
        $page = $this->actingAs($this->admin)->get(route('admin.setting.index'));
        $page->assertStatus(200);
        $page->assertSee('Pengaturan Sistem');
        $page->assertSee('Logo Resmi Sekolah');
        $page->assertSee('Sesi &amp; Akses Administrator', false);
        $page->assertSee('Keluar dari Admin');

        // 8b. Ganti Logo
        Storage::fake('public');
        $img = UploadedFile::fake()->image('logo_man1.png', 100, 100);
        $uploadLogo = $this->actingAs($this->admin)->post(route('admin.setting.logo'), [
            'logo' => $img,
        ]);
        $uploadLogo->assertRedirect(route('admin.setting.index'));
        $this->assertNotNull(Setting::get('app_logo'));

        // 8c. Reset Logo
        $resetLogo = $this->actingAs($this->admin)->post(route('admin.setting.logo.reset'));
        $resetLogo->assertRedirect(route('admin.setting.index'));
        $this->assertNull(Setting::get('app_logo'));

        // 8d. Ganti Password Admin
        $changePassword = $this->actingAs($this->admin)->post(route('admin.setting.password'), [
            'password_lama' => 'admin123',
            'password_baru' => 'sukses2026',
            'password_baru_confirmation' => 'sukses2026',
        ]);
        $changePassword->assertRedirect(route('admin.setting.index'));
        $this->admin->refresh();
        $this->assertTrue(Hash::check('sukses2026', $this->admin->password));
    }

    /** 9. Validasi Digital Mushaf Al-Qur'an 114 Surah */
    public function test_quran_helper_memiliki_114_surah_lengkap(): void
    {
        $surahList = Quran::surahs();
        $this->assertCount(114, $surahList);
        $this->assertEquals('1. Al-Fatihah', $surahList[0]);
        $this->assertEquals('114. An-Nas', $surahList[113]);
    }
}
