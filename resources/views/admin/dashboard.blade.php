@extends('layouts.app')

@section('title', 'Rekapitulasi Laporan Tahfidz')

@section('content')
<div class="ui-card gold-edge">
    <nav class="nav-tabs" aria-label="Menu Administrator">
        <a href="{{ route('admin.dashboard') }}" class="tab-btn active">
            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M18 20V10"/>
                <path d="M12 20V4"/>
                <path d="M6 20v-6"/>
            </svg>
            <span>Rekapitulasi Laporan</span>
        </a>
        <a href="{{ route('admin.siswa.index') }}" class="tab-btn">
            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            <span>Kelola Siswa</span>
        </a>
        <a href="{{ route('admin.petugas.index') }}" class="tab-btn">
            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M22 11l-3 3-2-2"/>
            </svg>
            <span>Kelola Petugas</span>
        </a>
        <a href="{{ route('admin.setting.index') }}" class="tab-btn">
            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
            </svg>
            <span>Pengaturan & Akun</span>
        </a>
    </nav>

    <!-- Banner Informasi Hari, Tanggal & Jam Live Administrator -->
    <div class="admin-welcome-banner">
        <div class="admin-welcome-text">
            <div class="admin-welcome-eyebrow">Panel Pengendali Administrator &middot; MAN 1 Probolinggo</div>
            <h2 class="admin-welcome-heading">Rekapitulasi Setoran Hafalan Siswa</h2>
            <p class="admin-welcome-desc">Pantau perkembangan tahfidz siswa, kelola data master, dan unduh laporan rekapitulasi setoran secara real-time.</p>
        </div>
        <div class="admin-datetime-card" id="dashboard-datetime-widget">
            <div class="datetime-card-header">
                <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span>Waktu Sistem Aktif</span>
            </div>
            <div class="datetime-card-day" id="dashboard-clock-day">{{ now()->locale('id')->isoFormat('dddd') }}</div>
            <div class="datetime-card-date" id="dashboard-clock-date">{{ now()->locale('id')->isoFormat('D MMMM Y') }}</div>
            <div class="datetime-card-time" id="dashboard-clock-time">{{ now()->format('H:i:s') }} <small>WIB</small></div>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
        <div>
            <h2>Tabel Rekapitulasi Laporan Setoran</h2>
            <p class="card-desc">Saring laporan setoran hafalan siswa dan ekspor data ke berkas CSV yang kompatibel dengan Microsoft Excel.</p>
        </div>
    </div>

    <!-- Statistik Ringkas Berdasarkan Filter Aktif -->
    <div class="stats-row" role="region" aria-label="Statistik Laporan">
        <div class="stat-box">
            <div class="stat-icon-wrap">
                <svg class="svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
            </div>
            <div>
                <b>{{ $totalLaporan }}</b>
                <span>Total Laporan Masuk</span>
            </div>
        </div>
        <div class="stat-box stat-cyan">
            <div class="stat-icon-wrap">
                <svg class="svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/>
                    <polyline points="16 7 22 7 22 13"/>
                </svg>
            </div>
            <div>
                <b>{{ $totalZiyadah }}</b>
                <span>Ziyadah (Hafalan Baru)</span>
            </div>
        </div>
        <div class="stat-box stat-gold">
            <div class="stat-icon-wrap">
                <svg class="svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="1 4 1 10 7 10"/>
                    <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
                </svg>
            </div>
            <div>
                <b>{{ $totalMurojaah }}</b>
                <span>Murojaah (Pengulangan)</span>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon-wrap" style="color: var(--pri-green); background-color: var(--soft-green);">
                <svg class="svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="8" r="7"/>
                    <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>
                </svg>
            </div>
            <div>
                <b>{{ $avgNilai > 0 ? $avgNilai : '-' }}</b>
                <span>Rerata Nilai ({{ $totalSantriUnik }} Santri)</span>
            </div>
        </div>
    </div>

    @if ($demoCount > 0)
        <div class="alert-box" style="background-color: #fff9e6; border: 1.5px solid #fed7aa; color: #9a3412;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <svg class="svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span><strong>Perhatian:</strong> Terdapat {{ $demoCount }} data laporan contoh demonstrasi awal dalam sistem.</span>
            </div>
            <form action="{{ route('admin.laporan.clear-demo') }}" method="POST" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua data demonstrasi awal?');">
                @csrf
                <button type="submit" class="btn-danger btn-sm" style="background-color: #ffffff; border-color: #ea580c; color: #c2410c;">
                    Bersihkan Data Demo
                </button>
            </form>
        </div>
    @endif

    <!-- Toolbar Filter dan Pencarian -->
    <form action="{{ route('admin.dashboard') }}" method="GET" class="filter-toolbar" id="filter-form">
        <select name="tahun" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Tahun Ajaran</option>
            @foreach ($tahunOptions as $th)
                <option value="{{ $th }}" {{ $filterTahun === $th ? 'selected' : '' }}>
                    Tahun {{ $th }}
                </option>
            @endforeach
        </select>

        <select name="kelas" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Kelas</option>
            @foreach ($kelasOptions as $kl)
                <option value="{{ $kl }}" {{ $filterKelas === $kl ? 'selected' : '' }}>
                    Kelas {{ $kl }}
                </option>
            @endforeach
        </select>

        <select name="kategori" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach ($kategoriOptions as $kat)
                <option value="{{ $kat }}" {{ $filterKategori === $kat ? 'selected' : '' }}>
                    {{ $kat }}
                </option>
            @endforeach
        </select>

        <div style="position: relative; flex: 2; min-width: 200px;">
            <svg class="svg-icon" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--ink-muted);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="search" 
                   name="q" 
                   class="form-input" 
                   style="padding-left: 42px;" 
                   placeholder="Cari siswa, petugas, surah, nilai..." 
                   value="{{ $search }}">
        </div>

        <a href="{{ route('admin.laporan.export', request()->query()) }}" class="btn-secondary btn-action" title="Unduh berkas CSV untuk Microsoft Excel">
            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            <span>Ekspor CSV</span>
        </a>
    </form>

    <!-- Tabel Rekapitulasi Data -->
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th scope="col">Waktu Setoran</th>
                    <th scope="col">Siswa</th>
                    <th scope="col">Kelas</th>
                    <th scope="col">Jurusan</th>
                    <th scope="col">Tahun</th>
                    <th scope="col">Petugas Penyimak</th>
                    <th scope="col">Kategori</th>
                    <th scope="col">Surah Al-Qur'an</th>
                    <th scope="col">Ayat</th>
                    <th scope="col" style="text-align: center;">Nilai & Predikat</th>
                    <th scope="col" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporanList as $item)
                    @php
                        $pred = $item->predikat;
                    @endphp
                    <tr>
                        <td style="white-space: nowrap; font-size: 13px; color: var(--ink-secondary);">
                            {{ $item->waktu_setoran->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div class="student-avatar" style="width: 36px; height: 36px; font-size: 15px;" title="{{ $item->nama_siswa }}">
                                    {{ mb_substr($item->nama_siswa, 0, 1) }}
                                </div>
                                <div style="min-width: 0;">
                                    <span style="font-weight: 700; color: var(--ink-primary); font-size: 14.5px; display: block;">
                                        {{ $item->nama_siswa }}
                                    </span>
                                    @if (str_contains($item->catatan ?? '', 'demonstrasi'))
                                        <span class="meta-tag tag-demo" style="margin-top: 2px;">Demo</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="meta-tag">Kelas {{ $item->kelas }}</span>
                        </td>
                        <td>{{ $item->jurusan ?? '-' }}</td>
                        <td style="font-size: 13.5px;">{{ $item->tahun_ajaran }}</td>
                        <td style="font-size: 13.5px; color: var(--ink-secondary);">{{ $item->nama_petugas }}</td>
                        <td>
                            <span class="meta-tag {{ $item->kategori === 'Ziyadah' ? 'tag-ziyadah' : 'tag-murojaah' }}">
                                {{ $item->kategori }}
                            </span>
                        </td>
                        <td style="font-weight: 600;">{{ $item->surah }}</td>
                        <td>Ayat {{ $item->ayat }}</td>
                        <td style="text-align: center;">
                            <div style="display: inline-flex; flex-direction: column; align-items: center; gap: 3px;">
                                <span style="font-family: 'Fraunces', serif; font-size: 18px; font-weight: 700; color: var(--pri-green); line-height: 1;">
                                    {{ $item->nilai }}
                                </span>
                                <span class="predikat-badge {{ $pred['class'] }}">
                                    {{ $pred['label'] }}
                                </span>
                            </div>
                        </td>
                        <td style="text-align: center; white-space: nowrap;">
                            <button type="button" 
                                    class="btn-danger btn-sm"
                                    onclick="openConfirmModal({
                                        title: 'Hapus Laporan Setoran',
                                        message: 'Apakah Anda yakin ingin menghapus laporan setoran untuk {{ addslashes($item->nama_siswa) }} ({{ addslashes($item->surah) }})?',
                                        actionUrl: '{{ route('admin.laporan.destroy', $item) }}',
                                        method: 'DELETE',
                                        confirmButtonText: 'Ya, Hapus Laporan'
                                    })"
                                    title="Hapus Laporan Ini">
                                <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                </svg>
                                <span>Hapus</span>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" style="text-align: center; padding: 48px 20px;">
                            <div class="empty-state" style="border: none; background: transparent; padding: 0;">
                                <h3>Tidak ada laporan setoran yang sesuai</h3>
                                <p>Coba ubah kata kunci pencarian atau filter yang sedang aktif.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 22px;">
        {{ $laporanList->links() }}
    </div>
</div>
@endsection
