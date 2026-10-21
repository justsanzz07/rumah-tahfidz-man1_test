<?php

namespace Tests\Feature;

use App\Models\LaporanSetoran;
use App\Models\Petugas;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TahfidzReportTest extends TestCase
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
            'nama' => 'Ustadz Abdullah',
            'is_active' => true,
        ]);

        $this->siswa = Siswa::create([
            'nama' => 'Ahmad Fauzi',
            'kelas' => 'X-1',
            'jurusan' => 'MIPA',
            'tahun_ajaran' => '2026/2027',
        ]);
    }

    public function test_petugas_page_can_be_rendered(): void
    {
        $response = $this->get(route('petugas.index'));

        $response->assertStatus(200);
        $response->assertSee('Laporan Rumah Tahfidz');
        $response->assertSee('MAN 1 PROBOLINGGO');
        $response->assertSee('Catat Setoran');
    }

    public function test_petugas_can_store_setoran_report(): void
    {
        $payload = [
            'waktu_setoran' => now()->format('Y-m-d H:i'),
            'petugas_id' => $this->petugas->id,
            'siswa_id' => $this->siswa->id,
            'kategori' => 'Ziyadah',
            'surah' => '1. Al-Fatihah',
            'ayat' => '1-7',
            'nilai' => '95',
        ];

        $response = $this->post(route('petugas.store'), $payload);

        $response->assertRedirect(route('petugas.index'));
        $this->assertDatabaseHas('laporan_setorans', [
            'nama_siswa' => 'Ahmad Fauzi',
            'nama_petugas' => 'Ustadz Abdullah',
            'kategori' => 'Ziyadah',
            'surah' => '1. Al-Fatihah',
            'ayat' => '1-7',
            'nilai' => '95',
        ]);
    }

    public function test_petugas_can_store_setoran_via_ajax(): void
    {
        $payload = [
            'waktu_setoran' => now()->format('Y-m-d H:i'),
            'petugas_id' => $this->petugas->id,
            'siswa_id' => $this->siswa->id,
            'kategori' => 'Murojaah',
            'surah' => '78. An-Naba',
            'ayat' => '1-20',
            'nilai' => '88',
        ];

        $response = $this->postJson(route('petugas.store'), $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $this->assertDatabaseHas('laporan_setorans', [
            'surah' => '78. An-Naba',
            'kategori' => 'Murojaah',
            'nilai' => '88',
        ]);
    }

    public function test_cannot_store_setoran_with_invalid_data(): void
    {
        $response = $this->post(route('petugas.store'), [
            'waktu_setoran' => '',
            'petugas_id' => 9999,
            'siswa_id' => 9999,
        ]);

        $response->assertSessionHasErrors(['waktu_setoran', 'petugas_id', 'siswa_id', 'kategori', 'surah', 'ayat', 'nilai']);
    }

    public function test_admin_dashboard_requires_authentication(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_login_requires_email_and_password(): void
    {
        $response = $this->post(route('login.post'), []);

        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function test_admin_can_login_with_email_and_password(): void
    {
        $response = $this->post(route('login.post'), [
            'email' => 'admin@man1probolinggo.sch.id',
            'password' => 'admin123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_admin_can_view_dashboard_and_export_csv(): void
    {
        LaporanSetoran::create([
            'waktu_setoran' => now(),
            'petugas_id' => $this->petugas->id,
            'nama_petugas' => $this->petugas->nama,
            'siswa_id' => $this->siswa->id,
            'nama_siswa' => $this->siswa->nama,
            'kelas' => $this->siswa->kelas,
            'jurusan' => $this->siswa->jurusan,
            'tahun_ajaran' => $this->siswa->tahun_ajaran,
            'kategori' => 'Ziyadah',
            'surah' => '1. Al-Fatihah',
            'ayat' => '1-7',
            'nilai' => '90',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Tabel Rekapitulasi Laporan Setoran');
        $response->assertSee('Ahmad Fauzi');

        $exportResponse = $this->actingAs($this->admin)->get(route('admin.laporan.export'));
        $exportResponse->assertStatus(200);
        $exportResponse->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_admin_can_delete_setoran_report(): void
    {
        $laporan = LaporanSetoran::create([
            'waktu_setoran' => now(),
            'petugas_id' => $this->petugas->id,
            'nama_petugas' => $this->petugas->nama,
            'siswa_id' => $this->siswa->id,
            'nama_siswa' => $this->siswa->nama,
            'kelas' => $this->siswa->kelas,
            'tahun_ajaran' => $this->siswa->tahun_ajaran,
            'kategori' => 'Ziyadah',
            'surah' => '1. Al-Fatihah',
            'ayat' => '1-7',
            'nilai' => '90',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.laporan.destroy', $laporan));

        $response->assertRedirect();
        $this->assertDatabaseMissing('laporan_setorans', [
            'id' => $laporan->id,
        ]);
    }

    public function test_admin_can_manage_siswa(): void
    {
        // 1. Create
        $response = $this->actingAs($this->admin)->post(route('admin.siswa.store'), [
            'nama' => 'Zaid bin Tsabit',
            'kelas' => 'XI-1',
            'jurusan' => 'Keagamaan',
            'tahun_ajaran' => '2026/2027',
        ]);
        $response->assertRedirect(route('admin.siswa.index'));
        $this->assertDatabaseHas('siswas', ['nama' => 'Zaid bin Tsabit']);

        $newSiswa = Siswa::where('nama', 'Zaid bin Tsabit')->firstOrFail();

        // 2. Update
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.siswa.update', $newSiswa), [
            'nama' => 'Zaid bin Tsabit Al-Anshari',
            'kelas' => 'XI-1',
            'jurusan' => 'Keagamaan',
            'tahun_ajaran' => '2026/2027',
        ]);
        $updateResponse->assertRedirect(route('admin.siswa.index'));
        $this->assertDatabaseHas('siswas', ['nama' => 'Zaid bin Tsabit Al-Anshari']);

        // 3. Delete
        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.siswa.destroy', $newSiswa));
        $deleteResponse->assertRedirect(route('admin.siswa.index'));
        $this->assertDatabaseMissing('siswas', ['id' => $newSiswa->id]);
    }

    public function test_admin_can_import_siswa_from_csv_text(): void
    {
        $rawText = "Bilal bin Rabah, X-2, IPS, 2026/2027\nUsamah bin Zaid, XII-1, MIPA, 2026/2027";

        $response = $this->actingAs($this->admin)->post(route('admin.siswa.import'), [
            'raw_text' => $rawText,
        ]);

        $response->assertRedirect(route('admin.siswa.index'));
        $this->assertDatabaseHas('siswas', ['nama' => 'Bilal bin Rabah', 'kelas' => 'X-2']);
        $this->assertDatabaseHas('siswas', ['nama' => 'Usamah bin Zaid', 'kelas' => 'XII-1']);
    }

    public function test_admin_can_manage_petugas(): void
    {
        // 1. Create
        $response = $this->actingAs($this->admin)->post(route('admin.petugas.store'), [
            'nama' => 'Ustadzah Maryam',
        ]);
        $response->assertRedirect(route('admin.petugas.index'));
        $this->assertDatabaseHas('petugas', ['nama' => 'Ustadzah Maryam']);

        $newPetugas = Petugas::where('nama', 'Ustadzah Maryam')->firstOrFail();

        // 2. Update
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.petugas.update', $newPetugas), [
            'nama' => 'Ustadzah Maryam Az-Zahra',
        ]);
        $updateResponse->assertRedirect(route('admin.petugas.index'));
        $this->assertDatabaseHas('petugas', ['nama' => 'Ustadzah Maryam Az-Zahra']);

        // 3. Delete
        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.petugas.destroy', $newPetugas));
        $deleteResponse->assertRedirect(route('admin.petugas.index'));
        $this->assertDatabaseMissing('petugas', ['id' => $newPetugas->id]);
    }

    public function test_admin_can_clear_demo_data(): void
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
            'surah' => '1. Al-Fatihah',
            'ayat' => '1-7',
            'nilai' => '90',
            'catatan' => 'Data demonstrasi awal',
        ]);

        $this->assertDatabaseHas('laporan_setorans', ['catatan' => 'Data demonstrasi awal']);

        $response = $this->actingAs($this->admin)->post(route('admin.laporan.clear-demo'));

        $response->assertRedirect();
        $this->assertDatabaseMissing('laporan_setorans', ['catatan' => 'Data demonstrasi awal']);
    }

    public function test_admin_can_access_pengaturan_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.setting.index'));

        $response->assertStatus(200);
        $response->assertSee('Pengaturan Sistem');
        $response->assertSee('Logo Aplikasi');
        $response->assertSee('Ganti Kata Sandi Admin');
    }

    public function test_admin_can_update_logo(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('logo_sekolah.png', 120, 120);

        $response = $this->actingAs($this->admin)->post(route('admin.setting.logo'), [
            'logo' => $file,
        ]);

        $response->assertRedirect(route('admin.setting.index'));
        $this->assertNotNull(Setting::get('app_logo'));
    }

    public function test_admin_can_reset_logo(): void
    {
        Setting::set('app_logo', 'uploads/test_logo.png');

        $response = $this->actingAs($this->admin)->post(route('admin.setting.logo.reset'));

        $response->assertRedirect(route('admin.setting.index'));
        $this->assertNull(Setting::get('app_logo'));
    }

    public function test_admin_can_update_password(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.setting.password'), [
            'password_lama' => 'admin123',
            'password_baru' => 'sandiBaru2026',
            'password_baru_confirmation' => 'sandiBaru2026',
        ]);

        $response->assertRedirect(route('admin.setting.index'));
        $this->admin->refresh();
        $this->assertTrue(Hash::check('sandiBaru2026', $this->admin->password));
    }
}
