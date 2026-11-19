@extends('layouts.app')

@section('title', $selectedSiswa ? 'Mutabaah Hafalan: ' . $selectedSiswa->nama : 'Pantau Hafalan Santri (Wali Murid)')

@section('content')
<!-- Header Khusus Cetak Dokumen Resmi -->
@if ($selectedSiswa)
    <div class="print-letterhead" aria-hidden="true">
        <h2>Kementerian Agama Republik Indonesia</h2>
        <h3>Madrasah Aliyah Negeri 1 Probolinggo &middot; Rumah Tahfidz</h3>
        <p>Buku Kendali Mutaba'ah &amp; Rekapitulasi Setoran Hafalan Al-Qur'an Siswa</p>
    </div>
@endif

<!-- Banner Sambutan Khusus Wali Santri -->
<div class="wali-banner no-print">
    <div class="wali-banner-left">
        <div class="wali-badge">
            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z"/>
            </svg>
            <span>Portal Wali Santri &middot; Akses Bebas Tanpa Login</span>
        </div>
        <h1 class="wali-banner-title">
            @if ($selectedSiswa)
                Mutaba'ah Hafalan: {{ $selectedSiswa->nama }}
            @else
                Pantau Perkembangan Hafalan Ananda
            @endif
        </h1>
        <p class="wali-banner-sub">
            @if ($selectedSiswa)
                Pantau capaian setoran hafalan Al-Qur'an (Ziyadah &amp; Murojaah), evaluasi nilai pembimbing, dan riwayat perkembangan ananda secara transparan.
            @else
                Ayah &amp; Bunda dapat mencari nama putra-putri tercinta untuk melihat buku mutaba'ah hafalan Al-Qur'an, riwayat setoran, serta nilai dari ustadz penyimak secara real-time.
            @endif
        </p>
    </div>
    <div class="pendamping-datetime-box" title="Waktu Akses Sistem">
        <div class="datetime-card-header">
            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <span>Waktu Akses</span>
        </div>
        <div class="datetime-card-day">{{ now()->locale('id')->isoFormat('dddd') }}</div>
        <div class="datetime-card-date">{{ now()->locale('id')->isoFormat('D MMMM Y') }}</div>
        <div class="datetime-card-time">{{ now()->format('H:i') }} <small>WIB</small></div>
    </div>
</div>

<!-- Kotak Pencarian Siswa (Tampil Utama atau Mode Switch Siswa) -->
<div class="wali-search-card no-print">
    <form action="{{ route('wali.index') }}" method="GET" class="wali-search-form" id="wali-search-form">
        <div class="wali-input-search-wrap">
            <label for="search-siswa-input" class="form-label" style="display: flex; justify-content: space-between;">
                <span>Cari Nama Santri / Ananda</span>
                <span style="font-size: 11.5px; color: var(--ink-muted); font-weight: 500;">Bisa ketik nama atau langsung pilih</span>
            </label>
            <div style="position: relative;">
                <svg class="svg-icon" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--ink-muted); pointer-events: none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="search" 
                       id="search-siswa-input" 
                       name="q" 
                       class="form-input" 
                       style="padding-left: 42px;" 
                       placeholder="Ketik nama ananda (mis. Ahmad Fauzi, Aisyah...)" 
                       value="{{ $search }}" 
                       autocomplete="off">
            </div>

            <!-- Autocomplete Suggestion Dropdown -->
            <div class="wali-autocomplete-dropdown" id="wali-autocomplete-dropdown" role="listbox" aria-label="Saran nama siswa"></div>
        </div>

        <div style="flex: 1; min-width: 180px;">
            <label for="filter-kelas-select" class="form-label">Filter Kelas</label>
            <select name="kelas" id="filter-kelas-select" class="form-select">
                <option value="">Semua Kelas</option>
                @foreach ($kelasList as $k)
                    <option value="{{ $k }}" {{ $selectedKelas === $k ? 'selected' : '' }}>
                        Kelas {{ $k }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn-primary" style="padding: 10px 18px;">
                <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <span>Cari</span>
            </button>
            @if ($search !== '' || $selectedKelas !== '' || $selectedSiswa)
                <a href="{{ route('wali.index') }}" class="btn-secondary" style="padding: 10px 14px;" title="Reset Pencarian">
                    Reset
                </a>
            @endif
        </div>
    </form>

    <!-- Quick Class Pills Filter -->
    <div class="quick-class-pills">
        <span style="font-size: 12px; color: var(--ink-muted); font-weight: 600; margin-right: 4px;">Pintas Kelas:</span>
        <a href="{{ route('wali.index') }}" class="quick-class-pill {{ empty($selectedKelas) && empty($selectedSiswa) ? 'active' : '' }}">
            Semua
        </a>
        @foreach ($kelasList as $k)
            <a href="{{ route('wali.index', ['kelas' => $k]) }}" class="quick-class-pill {{ $selectedKelas === $k ? 'active' : '' }}">
                Kelas {{ $k }}
            </a>
        @endforeach
    </div>
</div>

@if ($selectedSiswa)
    <!-- ==========================================================================
         TAMPILAN SISWA TERPILIH (PROFIL & PERKEMBANGAN HAFALAN)
         ========================================================================== -->

    <!-- Kartu Profil Santri Terpilih -->
    <section class="wali-profile-hero" aria-labelledby="student-profile-title">
        <div class="wali-profile-main">
            @php
                $initials = collect(explode(' ', $selectedSiswa->nama))
                    ->filter()
                    ->take(2)
                    ->map(fn($w) => mb_substr($w, 0, 1))
                    ->join('');
            @endphp
            <div class="wali-avatar-large" aria-hidden="true">
                {{ strtoupper($initials ?: 'S') }}
            </div>
            <div>
                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                    <h2 class="wali-profile-name" id="student-profile-title">{{ $selectedSiswa->nama }}</h2>
                    <span class="predikat-badge predikat-mumtaz" style="font-size: 11px;">Santri Aktif</span>
                </div>
                <div class="wali-profile-meta">
                    <span class="wali-meta-item">
                        <strong>Kelas:</strong> {{ $selectedSiswa->kelas }} {{ $selectedSiswa->jurusan ?? '' }}
                    </span>
                    <span class="wali-meta-item">
                        <strong>Tahun Ajaran:</strong> {{ $selectedSiswa->tahun_ajaran ?? '2026/2027' }}
                    </span>
                    <span class="wali-meta-item">
                        <strong>Institusi:</strong> MAN 1 Probolinggo
                    </span>
                </div>
            </div>
        </div>

        <div class="wali-action-buttons no-print">
            <button type="button" class="btn-secondary" id="btn-copy-link" title="Salin tautan profil ananda">
                <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                </svg>
                <span>Salin Tautan Profil</span>
            </button>

            <button type="button" class="btn-primary" onclick="window.print()" title="Cetak mutaba'ah hafalan ini ke kertas atau simpan sebagai PDF">
                <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"/>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                    <rect x="6" y="14" width="12" height="8"/>
                </svg>
                <span>Cetak / Simpan PDF</span>
            </button>

            <a href="{{ route('wali.index') }}" class="btn-secondary" title="Cari santri lainnya">
                <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <span>Cari Santri Lain</span>
            </a>
        </div>
    </section>

    <!-- Ringkasan Statistik Perkembangan Hafalan -->
    <section class="wali-stats-grid" aria-label="Statistik Perkembangan Hafalan Santri">
        <!-- Card 1: Total Setoran -->
        <div class="wali-stat-card card-accent-green">
            <div class="wali-stat-label">
                <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                </svg>
                <span>Total Setoran Disimak</span>
            </div>
            <div class="wali-stat-value">{{ $stats['total_setoran'] }} <small style="font-size: 14px; font-weight: 500; color: var(--ink-muted);">kali</small></div>
            <div class="wali-stat-sub">
                <span class="meta-tag tag-ziyadah" style="font-size: 11px;">{{ $stats['total_ziyadah'] }} Ziyadah</span>
                <span class="meta-tag tag-murojaah" style="font-size: 11px;">{{ $stats['total_murojaah'] }} Murojaah</span>
            </div>
        </div>

        <!-- Card 2: Rata-rata Nilai & Predikat -->
        <div class="wali-stat-card card-accent-gold">
            <div class="wali-stat-label">
                <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
                <span>Rata-Rata Nilai</span>
            </div>
            <div class="wali-stat-value">
                {{ $stats['total_setoran'] > 0 ? number_format($stats['average_nilai'], 1) : '-' }}
                @if ($stats['total_setoran'] > 0)
                    <small style="font-size: 13px; font-weight: 500; color: var(--ink-muted);">/ 100</small>
                @endif
            </div>
            <div class="wali-stat-sub">
                @if ($stats['total_setoran'] > 0)
                    <span class="predikat-badge {{ $stats['average_predikat']['class'] }}">
                        {{ $stats['average_predikat']['label'] }} ({{ $stats['average_predikat']['desc'] }})
                    </span>
                @else
                    <span style="color: var(--ink-muted);">Belum ada nilai setoran</span>
                @endif
            </div>
        </div>

        <!-- Card 3: Surah Al-Qur'an yang Dikuasai / Pernah Disetorkan -->
        <div class="wali-stat-card card-accent-cyan">
            <div class="wali-stat-label">
                <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                </svg>
                <span>Surah Dikuasai / Disetor</span>
            </div>
            <div class="wali-stat-value">{{ $stats['total_surah'] }} <small style="font-size: 14px; font-weight: 500; color: var(--ink-muted);">surah</small></div>
            <div class="wali-stat-sub" style="color: var(--ink-muted);">
                Terekam dalam riwayat mutaba'ah
            </div>
        </div>

        <!-- Card 4: Setoran Terakhir -->
        <div class="wali-stat-card card-accent-purple">
            <div class="wali-stat-label">
                <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
                <span>Setoran Terakhir</span>
            </div>
            @if ($stats['last_setoran'])
                <div style="font-weight: 700; color: var(--pri-green); font-size: 15px; margin-bottom: 2px;">
                    {{ $stats['last_setoran']->surah }} (Ayat {{ $stats['last_setoran']->ayat }})
                </div>
                <div class="wali-stat-sub" style="color: var(--ink-muted); font-size: 12px;">
                    {{ $stats['last_setoran']->waktu_setoran->locale('id')->isoFormat('D MMM Y &bull; HH:mm') }} WIB
                    <br>
                    <span>Disimak: {{ $stats['last_setoran']->nama_petugas }}</span>
                </div>
            @else
                <div class="wali-stat-value" style="font-size: 18px; color: var(--ink-muted);">-</div>
                <div class="wali-stat-sub" style="color: var(--ink-muted);">Belum pernah menyetor</div>
            @endif
        </div>
    </section>

    <!-- Daftar Surah yang Pernah Disetorkan (Capaian Surah) -->
    @if ($surahList->isNotEmpty())
        <section class="surah-chips-wrapper" aria-labelledby="surah-capaian-title">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
                <h3 id="surah-capaian-title" style="margin: 0; font-size: 16px; color: var(--pri-green); display: flex; align-items: center; gap: 8px;">
                    <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    <span>Daftar Surah yang Pernah Disetorkan Ananda ({{ $surahList->count() }} Surah)</span>
                </h3>
                <span style="font-size: 12px; color: var(--ink-muted);">Klik nama surah untuk menyaring riwayat di bawah</span>
            </div>
            <div class="surah-chips-grid">
                @foreach ($surahList as $surItem)
                    <a href="{{ route('wali.index', ['siswa_id' => $selectedSiswa->id, 'surah_filter' => $surItem['surah']]) }}" 
                       class="surah-chip {{ $filterSurah === $surItem['surah'] ? 'active' : '' }}" 
                       style="{{ $filterSurah === $surItem['surah'] ? 'background-color: var(--soft-green); border-color: var(--emerald-accent); font-weight: 700;' : '' }}">
                        <span>{{ $surItem['surah'] }}</span>
                        <span class="surah-chip-count" title="Banyaknya kali setoran untuk surah ini">{{ $surItem['count'] }}x</span>
                        <span class="surah-chip-score" title="Nilai tertinggi pada surah ini">&#9733; {{ $surItem['max_nilai'] }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Riwayat Setoran Lengkap (Buku Mutaba'ah Digital) -->
    <section class="ui-card gold-edge" aria-labelledby="mutabaah-title">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 12px;">
            <div>
                <h2 id="mutabaah-title" style="margin: 0; font-size: 20px;">Buku Kendali Mutaba'ah Hafalan</h2>
                <p class="card-desc" style="margin-bottom: 0;">Catatan lengkap tanggal setoran, surah, ayat, nilai pembimbing, dan evaluasi berkala.</p>
            </div>

            <!-- Filter Kategori & Pencarian Surah di dalam Riwayat -->
            <div class="no-print" style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
                <a href="{{ route('wali.index', ['siswa_id' => $selectedSiswa->id]) }}" 
                   class="quick-class-pill {{ empty($filterKategori) && empty($filterSurah) ? 'active' : '' }}">
                    Semua ({{ $stats['total_setoran'] }})
                </a>
                <a href="{{ route('wali.index', ['siswa_id' => $selectedSiswa->id, 'kategori' => 'Ziyadah']) }}" 
                   class="quick-class-pill {{ $filterKategori === 'Ziyadah' ? 'active' : '' }}">
                    Ziyadah ({{ $stats['total_ziyadah'] }})
                </a>
                <a href="{{ route('wali.index', ['siswa_id' => $selectedSiswa->id, 'kategori' => 'Murojaah']) }}" 
                   class="quick-class-pill {{ $filterKategori === 'Murojaah' ? 'active' : '' }}">
                    Murojaah ({{ $stats['total_murojaah'] }})
                </a>

                @if ($filterSurah !== '')
                    <span class="meta-tag tag-ziyadah" style="font-size: 12px;">
                        Surah: {{ $filterSurah }}
                        <a href="{{ route('wali.index', ['siswa_id' => $selectedSiswa->id, 'kategori' => $filterKategori]) }}" style="margin-left: 4px; color: inherit; text-decoration: none; font-weight: 700;">&times;</a>
                    </span>
                @endif
            </div>
        </div>

        @if ($riwayatSetorans->isEmpty())
            <div style="text-align: center; padding: 40px 20px; background: #fafaf7; border-radius: 12px; border: 1px dashed var(--border-line);">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--soft-green); color: var(--pri-green); margin: 0 auto 12px; display: grid; place-items: center;">
                    <svg class="svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <line x1="12" y1="18" x2="12" y2="12"/>
                        <line x1="9" y1="15" x2="15" y2="15"/>
                    </svg>
                </div>
                <h3 style="font-size: 17px; color: var(--ink-primary); margin: 0 0 6px;">
                    @if ($filterKategori !== '' || $filterSurah !== '')
                        Tidak ada riwayat setoran yang cocok dengan filter yang dipilih.
                    @else
                        Belum Ada Catatan Setoran Hafalan
                    @endif
                </h3>
                <p style="color: var(--ink-muted); font-size: 13.5px; max-width: 480px; margin: 0 auto;">
                    @if ($filterKategori !== '' || $filterSurah !== '')
                        <a href="{{ route('wali.index', ['siswa_id' => $selectedSiswa->id]) }}" style="color: var(--pri-green); font-weight: 600; text-decoration: underline;">Tampilkan seluruh riwayat</a>
                    @else
                        Ananda {{ $selectedSiswa->nama }} telah terdaftar aktif sebagai santri Rumah Tahfidz MAN 1 Probolinggo. Catatan setoran hafalan akan tampil di halaman ini secara otomatis saat ustadz penyimak merekam simakan.
                    @endif
                </p>
            </div>
        @else
            <div class="table-responsive">
                <table class="data-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th style="min-width: 170px;">Waktu &amp; Tanggal</th>
                            <th style="min-width: 110px;">Kategori</th>
                            <th style="min-width: 200px;">Surah &amp; Rentang Ayat</th>
                            <th style="min-width: 130px; text-align: center;">Nilai &amp; Predikat</th>
                            <th style="min-width: 160px;">Ustadz Pembimbing</th>
                            <th style="min-width: 220px;">Evaluasi / Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($riwayatSetorans as $index => $item)
                            @php
                                $pred = $item->predikat;
                            @endphp
                            <tr>
                                <td style="text-align: center; color: var(--ink-muted); font-size: 13px;">
                                    {{ $riwayatSetorans->firstItem() + $index }}
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--ink-primary);">
                                        {{ $item->waktu_setoran->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                    </div>
                                    <small style="color: var(--ink-muted); font-size: 12px;">
                                        Pukul {{ $item->waktu_setoran->format('H:i') }} WIB
                                    </small>
                                </td>
                                <td>
                                    @if ($item->kategori === 'Ziyadah')
                                        <span class="meta-tag tag-ziyadah">
                                            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="12" y1="5" x2="12" y2="19"/>
                                                <line x1="5" y1="12" x2="19" y2="12"/>
                                            </svg>
                                            Ziyadah
                                        </span>
                                    @else
                                        <span class="meta-tag tag-murojaah">
                                            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="1 4 1 10 7 10"/>
                                                <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
                                            </svg>
                                            Murojaah
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: var(--pri-green); font-size: 14.5px;">
                                        {{ $item->surah }}
                                    </div>
                                    <div style="font-size: 12.5px; color: var(--ink-secondary);">
                                        Ayat: <strong>{{ $item->ayat }}</strong>
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <div style="font-family: var(--font-serif); font-size: 19px; font-weight: 700; color: var(--pri-green); line-height: 1.1;">
                                        {{ $item->nilai }}
                                    </div>
                                    <span class="predikat-badge {{ $pred['class'] }}" style="margin-top: 3px;">
                                        {{ $pred['label'] }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--ink-primary); font-size: 13.5px;">
                                        {{ $item->nama_petugas }}
                                    </div>
                                    <small style="color: var(--ink-muted); font-size: 11.5px;">Ustadz Pembimbing</small>
                                </td>
                                <td>
                                    @if (! empty($item->catatan))
                                        <div class="setoran-catatan-box">
                                            &ldquo;{{ $item->catatan }}&rdquo;
                                        </div>
                                    @else
                                        <span style="color: var(--ink-muted); font-size: 12.5px;">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Navigasi Halaman Pagination -->
            <div class="pagination-wrapper no-print" style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div style="font-size: 13px; color: var(--ink-muted);">
                    Menampilkan <strong>{{ $riwayatSetorans->firstItem() }}</strong> s.d. <strong>{{ $riwayatSetorans->lastItem() }}</strong> dari <strong>{{ $riwayatSetorans->total() }}</strong> total setoran
                </div>
                <div>
                    {{ $riwayatSetorans->links() }}
                </div>
            </div>
        @endif
    </section>

@else
    <!-- ==========================================================================
         TAMPILAN BELUM MEMILIH SISWA (HASIL CARI / PANDUAN AWAL WALI SANTRI)
         ========================================================================== -->

    @if ($searchResults->isNotEmpty())
        <!-- Hasil Pencarian Siswa -->
        <section class="ui-card gold-edge" aria-labelledby="hasil-cari-title">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                <div>
                    <h2 id="hasil-cari-title" style="margin: 0; font-size: 20px;">Hasil Pencarian Siswa</h2>
                    <p class="card-desc" style="margin-bottom: 0;">
                        Ditemukan <strong>{{ $searchResults->count() }}</strong> santri
                        @if ($search !== '') dengan nama mengandung &ldquo;<strong>{{ $search }}</strong>&rdquo;@endif
                        @if ($selectedKelas !== '') di <strong>Kelas {{ $selectedKelas }}</strong>@endif.
                        Silakan pilih untuk melihat perkembangan hafalan.
                    </p>
                </div>
                <span class="pill-count">{{ $searchResults->count() }} santri</span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 14px;">
                @foreach ($searchResults as $s)
                    @php
                        $inits = collect(explode(' ', $s->nama))->filter()->take(2)->map(fn($w) => mb_substr($w, 0, 1))->join('');
                    @endphp
                    <a href="{{ route('wali.index', ['siswa_id' => $s->id]) }}" 
                       class="ui-card" 
                       style="padding: 16px; text-decoration: none; display: flex; align-items: center; gap: 14px; border: 1.5px solid var(--border-line); transition: all 0.2s ease; background: #ffffff;"
                       onmouseover="this.style.borderColor='var(--emerald-accent)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='var(--shadow-hover)';"
                       onmouseout="this.style.borderColor='var(--border-line)'; this.style.transform='none'; this.style.boxShadow='var(--shadow-card)';">
                        <div class="wali-avatar-large" style="width: 48px; height: 48px; font-size: 18px; border-radius: 12px; flex-shrink: 0;" aria-hidden="true">
                            {{ strtoupper($inits ?: 'S') }}
                        </div>
                        <div style="min-width: 0; flex: 1;">
                            <div style="font-weight: 700; color: var(--pri-green); font-size: 15px; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $s->nama }}
                            </div>
                            <div style="font-size: 12.5px; color: var(--ink-secondary); display: flex; gap: 6px; align-items: center; flex-wrap: wrap;">
                                <span class="meta-tag" style="margin: 0; font-size: 11px;">Kelas {{ $s->kelas }} {{ $s->jurusan ?? '' }}</span>
                                <span style="font-size: 11.5px; color: var(--ink-muted);">&bull; {{ $s->laporan_setorans_count }} setoran</span>
                            </div>
                        </div>
                        <div style="color: var(--pri-green);">
                            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

    @elseif ($search !== '' || $selectedKelas !== '')
        <!-- State Tidak Ditemukan -->
        <section class="ui-card" style="text-align: center; padding: 48px 24px;">
            <div style="width: 56px; height: 56px; border-radius: 50%; background: #fee2e2; color: #b91c1c; margin: 0 auto 16px; display: grid; place-items: center;">
                <svg class="svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    <line x1="8" y1="11" x2="14" y2="11"/>
                </svg>
            </div>
            <h2 style="font-size: 19px; margin: 0 0 8px; color: var(--ink-primary);">Santri Tidak Ditemukan</h2>
            <p style="color: var(--ink-muted); font-size: 14px; max-width: 480px; margin: 0 auto 20px;">
                Tidak ada data santri yang cocok dengan pencarian 
                @if ($search !== '') &ldquo;<strong>{{ $search }}</strong>&rdquo;@endif
                @if ($selectedKelas !== '') di <strong>Kelas {{ $selectedKelas }}</strong>@endif.
                Pastikan penulisan nama sudah tepat atau coba cari dengan nama panggilan pendek.
            </p>
            <a href="{{ route('wali.index') }}" class="btn-secondary">
                Lihat Semua Kelas
            </a>
        </section>

    @else
        <!-- Panduan & Tata Cara Akses Awal untuk Orang Tua -->
        <section class="ui-card gold-edge" aria-labelledby="panduan-title">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                <div style="width: 34px; height: 34px; border-radius: 10px; background-color: var(--soft-green); color: var(--pri-green); display: grid; place-items: center;">
                    <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="16" x2="12" y2="12"/>
                        <line x1="12" y1="8" x2="12.01" y2="8"/>
                    </svg>
                </div>
                <h2 id="panduan-title" style="margin: 0; font-size: 20px;">Panduan Pengecekan Hafalan Ananda</h2>
            </div>
            <p class="card-desc">Portal ini dirancang khusus bagi orang tua/wali santri agar dapat memantau proses tahfidz Al-Qur'an secara mudah dan mandiri.</p>

            <div class="wali-guide-grid">
                <div style="display: flex; gap: 14px; align-items: flex-start; padding: 14px; background: #fafcfb; border-radius: 10px; border: 1px solid var(--border-line);">
                    <div style="width: 28px; height: 28px; border-radius: 50%; background: var(--pri-green); color: #ffffff; display: grid; place-items: center; font-weight: 700; font-size: 13px; flex-shrink: 0;">1</div>
                    <div>
                        <strong style="color: var(--ink-primary); font-size: 14.5px;">Cari Nama Ananda</strong>
                        <p style="margin: 3px 0 0; font-size: 13px; color: var(--ink-muted);">Ketik nama ananda pada kolom pencarian di atas atau pilih kelas ananda untuk menyaring daftar santri.</p>
                    </div>
                </div>

                <div style="display: flex; gap: 14px; align-items: flex-start; padding: 14px; background: #fafcfb; border-radius: 10px; border: 1px solid var(--border-line);">
                    <div style="width: 28px; height: 28px; border-radius: 50%; background: var(--pri-green); color: #ffffff; display: grid; place-items: center; font-weight: 700; font-size: 13px; flex-shrink: 0;">2</div>
                    <div>
                        <strong style="color: var(--ink-primary); font-size: 14.5px;">Pilih Profil Ananda</strong>
                        <p style="margin: 3px 0 0; font-size: 13px; color: var(--ink-muted);">Klik kartu atau nama santri yang sesuai untuk membuka buku kendali mutaba'ah lengkap ananda.</p>
                    </div>
                </div>

                <div style="display: flex; gap: 14px; align-items: flex-start; padding: 14px; background: #fafcfb; border-radius: 10px; border: 1px solid var(--border-line);">
                    <div style="width: 28px; height: 28px; border-radius: 50%; background: var(--pri-green); color: #ffffff; display: grid; place-items: center; font-weight: 700; font-size: 13px; flex-shrink: 0;">3</div>
                    <div>
                        <strong style="color: var(--ink-primary); font-size: 14.5px;">Pantau Nilai &amp; Catatan Ustadz</strong>
                        <p style="margin: 3px 0 0; font-size: 13px; color: var(--ink-muted);">Lihat surah yang sudah dikuasai, rata-rata nilai, predikat (Mumtaz, Jayyid, dll), serta evaluasi ustadz penyimak.</p>
                    </div>
                </div>

                <div style="display: flex; gap: 14px; align-items: flex-start; padding: 14px; background: #fafcfb; border-radius: 10px; border: 1px solid var(--border-line);">
                    <div style="width: 28px; height: 28px; border-radius: 50%; background: var(--pri-green); color: #ffffff; display: grid; place-items: center; font-weight: 700; font-size: 13px; flex-shrink: 0;">4</div>
                    <div>
                        <strong style="color: var(--ink-primary); font-size: 14.5px;">Simpan Tautan / Cetak Laporan</strong>
                        <p style="margin: 3px 0 0; font-size: 13px; color: var(--ink-muted);">Ayah/Bunda dapat menyalin tautan langsung untuk disimpan di bookmark, serta mencetak bukti mutaba'ah.</p>
                    </div>
                </div>
            </div>
        </section>
    @endif

@endif

<!-- Footer Portal Wali Santri & Tautan Akses Admin -->
<footer class="wali-footer no-print">
    <div class="wali-footer-text">
        &copy; {{ date('Y') }} Rumah Tahfidz MAN 1 Probolinggo &middot; Kementerian Agama Republik Indonesia
    </div>
    <div class="wali-footer-links">
        <a href="{{ route('wali.index') }}">Pantau Hafalan</a>
        <span class="footer-sep">&bull;</span>
        <a href="{{ route('petugas.index') }}">Halaman Setoran</a>
        <span class="footer-sep">&bull;</span>
        <a href="{{ route('admin.login') }}" class="footer-admin-link">
            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            <span>Masuk Panel Admin</span>
        </a>
    </div>
</footer>

@endsection

@section('scripts')
<script>
    // Copy Direct Student Profile Link to Clipboard
    const copyLinkBtn = document.getElementById('btn-copy-link');
    if (copyLinkBtn) {
        copyLinkBtn.addEventListener('click', function() {
            const currentUrl = window.location.href;
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(currentUrl).then(() => {
                    if (window.showToast) {
                        window.showToast('Tautan profil ananda berhasil disalin!');
                    }
                }).catch(() => {
                    fallbackCopy(currentUrl);
                });
            } else {
                fallbackCopy(currentUrl);
            }
        });

        function fallbackCopy(text) {
            const tempInput = document.createElement('input');
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            if (window.showToast) {
                window.showToast('Tautan profil ananda berhasil disalin!');
            }
        }
    }

    // Autocomplete / Live Instant Search for Students
    const searchInput = document.getElementById('search-siswa-input');
    const classSelect = document.getElementById('filter-kelas-select');
    const dropdown = document.getElementById('wali-autocomplete-dropdown');
    let debounceTimer = null;

    if (searchInput && dropdown) {
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const val = this.value.trim();
            const kelasVal = classSelect ? classSelect.value : '';

            if (val.length < 2 && !kelasVal) {
                dropdown.style.display = 'none';
                dropdown.innerHTML = '';
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`{{ route('wali.search-api') }}?q=${encodeURIComponent(val)}&kelas=${encodeURIComponent(kelasVal)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            dropdown.innerHTML = '';
                            data.forEach(item => {
                                const el = document.createElement('a');
                                el.href = item.url;
                                el.className = 'wali-autocomplete-item';
                                el.setAttribute('role', 'option');
                                el.innerHTML = `
                                    <div>
                                        <div style="font-weight: 700; color: var(--pri-green); font-size: 14px;">${escapeHtml(item.nama)}</div>
                                        <div style="font-size: 12px; color: var(--ink-muted);">Kelas ${escapeHtml(item.detail_kelas)}</div>
                                    </div>
                                    <div style="text-align: right;">
                                        <span class="meta-tag tag-ziyadah" style="font-size: 11px;">${item.total_setoran} setoran</span>
                                    </div>
                                `;
                                dropdown.appendChild(el);
                            });
                            dropdown.style.display = 'block';
                        } else {
                            dropdown.innerHTML = `
                                <div style="padding: 10px 14px; font-size: 13px; color: var(--ink-muted); text-align: center;">
                                    Tidak ada santri yang cocok dengan "${escapeHtml(val)}"
                                </div>
                            `;
                            dropdown.style.display = 'block';
                        }
                    })
                    .catch(() => {
                        dropdown.style.display = 'none';
                    });
            }, 250);
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });

        // Close on Escape key
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                dropdown.style.display = 'none';
            }
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text || '';
        return div.innerHTML;
    }
</script>
@endsection

