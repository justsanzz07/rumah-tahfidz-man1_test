<?php

namespace Tests\Feature;

use App\Models\LaporanSetoran;
use App\Models\Petugas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WaliSantriTest extends TestCase
{
    use RefreshDatabase;

    protected Petugas $petugas;

    protected Siswa $siswaA;

    protected Siswa $siswaB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->petugas = Petugas::create([
            'nama' => 'Ustadz Ahmad Dahlan',
            'is_active' => true,
        ]);

        $this->siswaA = Siswa::create([
            'nama' => 'Muhammad Rifqi',
            'kelas' => 'X-1',
            'jurusan' => 'MIPA',
            'tahun_ajaran' => '2026/2027',
        ]);

        $this->siswaB = Siswa::create([
            'nama' => 'Aisyah Putri',
            'kelas' => 'XI-2',
            'jurusan' => 'IPS',
            'tahun_ajaran' => '2026/2027',
        ]);
    }

    public function test_wali_page_can_be_accessed_without_login(): void
    {
        $response = $this->get(route('wali.index'));

        $response->assertStatus(200);
        $response->assertSee('Portal Wali Santri');
        $response->assertSee('Pantau Perkembangan Hafalan Ananda');
        $response->assertSee('Cari Nama Santri / Ananda');
        $response->assertSee('Panduan Pengecekan Hafalan Ananda');
    }

    public function test_default_home_route_serves_pantau_hafalan(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Portal Wali Santri');
        $response->assertSee('Pantau Perkembangan Hafalan Ananda');

        // URL alias /pantau-hafalan also serves the same page
        $aliasResponse = $this->get('/pantau-hafalan');
        $aliasResponse->assertStatus(200);
        $aliasResponse->assertSee('Portal Wali Santri');
    }

    public function test_wali_can_search_student_by_name(): void
    {
        $response = $this->get(route('wali.index', ['q' => 'Rifqi']));

        $response->assertStatus(200);
        $response->assertSee('Hasil Pencarian Siswa');
        $response->assertSee('Muhammad Rifqi');
        $response->assertDontSee('Aisyah Putri');
    }

    public function test_wali_can_filter_student_by_class(): void
    {
        $response = $this->get(route('wali.index', ['kelas' => 'XI-2']));

        $response->assertStatus(200);
        $response->assertSee('Hasil Pencarian Siswa');
        $response->assertSee('Aisyah Putri');
        $response->assertDontSee('Muhammad Rifqi');
    }

    public function test_wali_can_view_student_progress_and_history(): void
    {
        LaporanSetoran::create([
            'waktu_setoran' => now()->subDay(),
            'petugas_id' => $this->petugas->id,
            'nama_petugas' => $this->petugas->nama,
            'siswa_id' => $this->siswaA->id,
            'nama_siswa' => $this->siswaA->nama,
            'kelas' => $this->siswaA->kelas,
            'jurusan' => $this->siswaA->jurusan,
            'tahun_ajaran' => $this->siswaA->tahun_ajaran,
            'kategori' => 'Ziyadah',
            'surah' => '78. An-Naba',
            'ayat' => '1-20',
            'nilai' => 94,
            'catatan' => 'Tajwid sangat tartil dan makhraj fasih',
        ]);

        LaporanSetoran::create([
            'waktu_setoran' => now(),
            'petugas_id' => $this->petugas->id,
            'nama_petugas' => $this->petugas->nama,
            'siswa_id' => $this->siswaA->id,
            'nama_siswa' => $this->siswaA->nama,
            'kelas' => $this->siswaA->kelas,
            'jurusan' => $this->siswaA->jurusan,
            'tahun_ajaran' => $this->siswaA->tahun_ajaran,
            'kategori' => 'Murojaah',
            'surah' => '1. Al-Fatihah',
            'ayat' => '1-7',
            'nilai' => 86,
            'catatan' => 'Lancar dan tertib',
        ]);

        $response = $this->get(route('wali.index', ['siswa_id' => $this->siswaA->id]));

        $response->assertStatus(200);
        $response->assertSee('Muhammad Rifqi');
        $response->assertSee('X-1');
        $response->assertSee('2'); // total setoran
        $response->assertSee('90.0'); // rata-rata nilai
        $response->assertSee('Mumtaz'); // predikat rata-rata >= 90
        $response->assertSee('78. An-Naba');
        $response->assertSee('1. Al-Fatihah');
        $response->assertSee('Tajwid sangat tartil dan makhraj fasih');
        $response->assertSee('Salin Tautan Profil');
        $response->assertSee('Cetak / Simpan PDF');
    }

    public function test_wali_can_filter_setoran_by_kategori(): void
    {
        LaporanSetoran::create([
            'waktu_setoran' => now()->subDay(),
            'petugas_id' => $this->petugas->id,
            'nama_petugas' => $this->petugas->nama,
            'siswa_id' => $this->siswaA->id,
            'nama_siswa' => $this->siswaA->nama,
            'kelas' => $this->siswaA->kelas,
            'jurusan' => $this->siswaA->jurusan,
            'tahun_ajaran' => $this->siswaA->tahun_ajaran,
            'kategori' => 'Ziyadah',
            'surah' => '78. An-Naba',
            'ayat' => '1-20',
            'nilai' => 95,
        ]);

        LaporanSetoran::create([
            'waktu_setoran' => now(),
            'petugas_id' => $this->petugas->id,
            'nama_petugas' => $this->petugas->nama,
            'siswa_id' => $this->siswaA->id,
            'nama_siswa' => $this->siswaA->nama,
            'kelas' => $this->siswaA->kelas,
            'jurusan' => $this->siswaA->jurusan,
            'tahun_ajaran' => $this->siswaA->tahun_ajaran,
            'kategori' => 'Murojaah',
            'surah' => '1. Al-Fatihah',
            'ayat' => '1-7',
            'nilai' => 88,
        ]);

        $response = $this->get(route('wali.index', [
            'siswa_id' => $this->siswaA->id,
            'kategori' => 'Ziyadah',
        ]));

        $response->assertStatus(200);
        $response->assertSee('78. An-Naba');
        $riwayatData = $response->viewData('riwayatSetorans');
        $this->assertCount(1, $riwayatData);
        $this->assertEquals('Ziyadah', $riwayatData->first()->kategori);
        $this->assertEquals('78. An-Naba', $riwayatData->first()->surah);
    }

    public function test_wali_direct_permalink_redirects_to_student_report(): void
    {
        $response = $this->get(route('wali.show', $this->siswaA));

        $response->assertRedirect(route('wali.index', ['siswa_id' => $this->siswaA->id]));
    }

    public function test_wali_search_api_returns_matching_students_as_json(): void
    {
        $response = $this->getJson(route('wali.search-api', ['q' => 'Rifqi']));

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment([
            'nama' => 'Muhammad Rifqi',
            'kelas' => 'X-1',
        ]);
    }

    public function test_wali_search_api_returns_empty_when_no_query(): void
    {
        $response = $this->getJson(route('wali.search-api'));

        $response->assertStatus(200);
        $response->assertExactJson([]);
    }

    public function test_wali_empty_history_shows_friendly_message(): void
    {
        $response = $this->get(route('wali.index', ['siswa_id' => $this->siswaB->id]));

        $response->assertStatus(200);
        $response->assertSee('Aisyah Putri');
        $response->assertSee('Belum Ada Catatan Setoran Hafalan');
    }

    public function test_admin_login_page_is_accessible_via_admin_login_route(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('Masuk ke Panel Administrator');
        $response->assertSee('admin@man1probolinggo.sch.id');
    }

    public function test_visiting_login_page_always_shows_login_form_and_does_not_auto_redirect(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin_test@man1probolinggo.sch.id',
            'role' => 'admin',
        ]);

        // Login sebagai admin
        $this->actingAs($admin);
        $this->assertAuthenticatedAs($admin);

        // Mengakses halaman login harus tetap menampilkan form login, bukan langsung bypass ke dashboard
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('Masuk ke Panel Administrator');
        $this->assertGuest();
    }
}
