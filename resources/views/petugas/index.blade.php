@extends('layouts.app')

@section('title', 'Pencatatan Setoran Hafalan')

@section('content')
<!-- Banner Informasi Hari, Tanggal & Jam Live Pendamping -->
<div class="pendamping-header-banner">
    <div class="pendamping-banner-left">
        <div class="pendamping-banner-badge">
            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z"/>
            </svg>
            <span>Halaman Pendamping &middot; Rumah Tahfidz</span>
        </div>
        <h2 class="pendamping-banner-title">Pencatatan &amp; Simakan Hafalan Al-Qur'an</h2>
        <p class="pendamping-banner-sub">Simak setoran hafalan siswa (Ziyadah &amp; Murojaah), verifikasi bacaan melalui Mushaf Digital, dan catat nilai secara terpadu.</p>
    </div>
    <div class="pendamping-datetime-box" id="pendamping-datetime-box" title="Waktu Nyata Setoran">
        <div class="datetime-card-header">
            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <span>Waktu Nyata Sekarang</span>
        </div>
        <div class="datetime-card-day" id="pendamping-clock-day">{{ now()->locale('id')->isoFormat('dddd') }}</div>
        <div class="datetime-card-date" id="pendamping-clock-date">{{ now()->locale('id')->isoFormat('D MMMM Y') }}</div>
        <div class="datetime-card-time" id="pendamping-clock-time">{{ now()->format('H:i:s') }} <small>WIB</small></div>
    </div>
</div>

<div class="page-grid-2col">
    <!-- Kolom Kiri: Formulir Pencatatan Setoran -->
    <section class="ui-card gold-edge" aria-labelledby="form-setoran-title">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
            <div style="width: 32px; height: 32px; border-radius: 9px; background-color: var(--soft-green); color: var(--pri-green); display: grid; place-items: center;">
                <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L15 8.5L22 9L17 14L18.5 21L12 17.5L5.5 21L7 14L2 9L9 8.5L12 2Z" fill="rgba(197, 155, 39, 0.25)" stroke="#c59b27"/>
                </svg>
            </div>
            <h2 id="form-setoran-title" style="margin: 0;">Formulir Catat Setoran</h2>
        </div>
        <p class="card-desc">Lengkapi formulir setoran hafalan siswa untuk memperbarui rekapitulasi data.</p>

        @if ($petugasList->isEmpty() || $siswaList->isEmpty())
            <div class="alert-box alert-danger" style="margin-bottom: 16px;">
                <span>Data master petugas atau siswa masih kosong. Harap <a href="{{ route('login') }}" style="color: inherit; text-decoration: underline; font-weight: 700;">masuk sebagai Admin</a> untuk menambahkan data master.</span>
            </div>
        @endif

        <form action="{{ route('petugas.store') }}" method="POST" id="form-catat-setoran">
            @csrf

            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <label for="waktu_setoran" class="form-label" style="margin-bottom: 0;">Waktu Setoran</label>
                    <button type="button" class="btn-secondary" id="btn-now-waktu-setoran" style="padding: 2px 8px; font-size: 11.5px;" title="Atur ke waktu saat ini">
                        Gunakan Waktu Sekarang
                    </button>
                </div>
                <input type="datetime-local" 
                       id="waktu_setoran" 
                       name="waktu_setoran" 
                       class="form-input" 
                       value="{{ old('waktu_setoran', now()->format('Y-m-d\TH:i')) }}" 
                       required>
            </div>

            <div class="form-group">
                <label for="petugas_id" class="form-label">Nama Petugas Penyimak</label>
                <select id="petugas_id" name="petugas_id" class="form-select" required>
                    <option value="">Pilih nama petugas</option>
                    @foreach ($petugasList as $p)
                        <option value="{{ $p->id }}" {{ old('petugas_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="filter_kelas" class="form-label">Saring Pilihan Siswa Berdasarkan Kelas</label>
                <select id="filter_kelas" class="form-select">
                    <option value="">Tampilkan semua kelas</option>
                    @foreach ($kelasList as $k)
                        <option value="{{ $k }}" {{ $selectedKelas === $k ? 'selected' : '' }}>
                            Kelas {{ $k }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="siswa_id" class="form-label">Nama Siswa</label>
                <select id="siswa_id" name="siswa_id" class="form-select" required>
                    <option value="">Pilih nama siswa</option>
                    @foreach ($siswaList as $s)
                        @php
                            $detail = $s->kelas . ($s->jurusan ? ' ' . $s->jurusan : '');
                        @endphp
                        <option value="{{ $s->id }}" 
                                data-kelas="{{ $s->kelas }}"
                                {{ old('siswa_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->nama }} (Kelas {{ $detail }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="kategori" class="form-label">Kategori Setoran</label>
                <select id="kategori" name="kategori" class="form-select" required>
                    <option value="">Pilih kategori setoran</option>
                    @foreach ($kategoriList as $kat)
                        <option value="{{ $kat }}" {{ old('kategori') === $kat ? 'selected' : '' }}>
                            {{ $kat }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="input-row">
                <div class="form-group" style="flex: 1.5;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; flex-wrap: wrap; gap: 6px;">
                        <label for="surah" class="form-label" style="margin-bottom: 0;">Surah Al-Qur'an</label>
                        <button type="button" class="quran-btn-trigger" id="btn-open-quran-modal" title="Buka Mushaf Digital & Referensi 114 Surah (API Al-Qur'an)">
                            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                                <path d="M6 6h10"/>
                                <path d="M6 10h10"/>
                            </svg>
                            <span>Buka Mushaf (API)</span>
                        </button>
                    </div>

                    <select id="surah" name="surah" class="form-select" required>
                        <option value="">Pilih surah</option>
                        @foreach ($surahList as $sur)
                            <option value="{{ $sur }}" {{ old('surah') === $sur ? 'selected' : '' }}>
                                {{ $sur }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Pratinjau Info Surah Terpilih dari API -->
                    <div id="quran-surah-preview" class="quran-surah-preview-card" style="display: none;">
                        <div style="display: flex; align-items: center; gap: 10px; min-width: 0;">
                            <span class="quran-num-badge" id="preview-surah-num">1</span>
                            <div style="min-width: 0;">
                                <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                    <strong id="preview-surah-latin" style="color: var(--pri-green); font-size: 14px;">Al-Fatihah</strong>
                                    <span class="meta-tag tag-ziyadah" id="preview-surah-type" style="font-size: 11px;">Makkiyyah</span>
                                </div>
                                <div style="font-size: 12px; color: var(--ink-muted);" id="preview-surah-meta">
                                    Arti: <span id="preview-surah-arti">Pembukaan</span> &middot; <span id="preview-surah-total">7</span> Ayat
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
                            <span class="quran-arabic-title" id="preview-surah-arabic">الفاتحة</span>
                            <button type="button" class="btn-secondary" id="btn-preview-read-ayahs" style="padding: 5px 10px; font-size: 11.5px; white-space: nowrap;" title="Lihat ayat-ayat surah ini">
                                Lihat Ayat
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group col-sm">
                    <label for="ayat" class="form-label">Jumlah / Rentang Ayat</label>
                    <input type="text" 
                           id="ayat" 
                           name="ayat" 
                           class="form-input" 
                           placeholder="mis. 1-10" 
                           value="{{ old('ayat') }}" 
                           required>
                    <small id="ayat-hint" style="color: var(--ink-muted); font-size: 11.5px; display: block; margin-top: 4px;">
                        Contoh: 1-7 atau 1 s.d. selesai
                    </small>
                </div>
            </div>

            <div class="form-group">
                <label for="nilai" class="form-label">Nilai Setoran (0 s.d. 100)</label>
                <input type="number" 
                       id="nilai" 
                       name="nilai" 
                       class="form-input" 
                       placeholder="mis. 85" 
                       min="0" 
                       max="100" 
                       value="{{ old('nilai') }}" 
                       required>
            </div>

            <!-- Kartu Umpan Balik Predikat Interaktif -->
            <div class="eval-live-card" id="eval-live-card" style="display: none;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 13px; font-weight: 600; color: var(--ink-secondary);">Predikat Hasil:</span>
                    <span id="eval-predikat-badge" class="predikat-badge"></span>
                </div>
                <span id="eval-predikat-desc" style="font-size: 12.5px; color: var(--ink-muted); font-weight: 500;"></span>
            </div>

            <button type="submit" class="btn-primary btn-full" id="btn-submit-setoran">
                <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                    <polyline points="17 21 17 13 7 13 7 21"/>
                    <polyline points="7 3 7 8 15 8"/>
                </svg>
                <span>Simpan Laporan Setoran</span>
            </button>
        </form>
    </section>

    <!-- Kolom Kanan: Riwayat Setoran Terbaru -->
    <section class="ui-card" aria-labelledby="riwayat-title">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; gap: 10px; flex-wrap: wrap;">
            <div>
                <h2 id="riwayat-title">Riwayat Setoran Terbaru</h2>
                <p class="card-desc" style="margin-bottom: 0;">Catatan setoran hafalan terbaru yang masuk ke sistem secara berurutan.</p>
            </div>
            <span class="pill-count" id="riwayat-count">
                <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
                {{ $totalLaporan }} laporan
            </span>
        </div>

        <form action="{{ route('petugas.index') }}" method="GET" style="margin-bottom: 18px;">
            <div style="position: relative;">
                <svg class="svg-icon" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--ink-muted);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="search" 
                       name="q" 
                       id="search-input" 
                       class="form-input" 
                       style="padding-left: 42px;" 
                       placeholder="Cari siswa, petugas, surah, kelas, atau nilai..." 
                       value="{{ $search }}">
            </div>
        </form>

        <div id="riwayat-list-container">
            @forelse ($riwayatList as $r)
                @php
                    $pred = $r->predikat;
                @endphp
                <article class="history-item">
                    <div class="student-avatar" title="{{ $r->nama_siswa }}">
                        {{ mb_substr($r->nama_siswa, 0, 1) }}
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div class="history-name">
                            <span>{{ $r->nama_siswa }}</span>
                        </div>
                        <div style="margin: 4px 0;">
                            <span class="meta-tag">Kelas {{ $r->kelas }}{{ $r->jurusan ? ' ' . $r->jurusan : '' }}</span>
                            <span class="meta-tag {{ $r->kategori === 'Ziyadah' ? 'tag-ziyadah' : 'tag-murojaah' }}">{{ $r->kategori }}</span>
                            @if (str_contains($r->catatan ?? '', 'demonstrasi'))
                                <span class="meta-tag tag-demo">Demo</span>
                            @endif
                        </div>
                        <div class="history-sub">
                            <strong>{{ $r->surah }}</strong> &middot; Ayat {{ $r->ayat }}<br>
                            {{ $r->waktu_setoran->isoFormat('D MMMM Y, HH:mm') }} &middot; Disimak oleh {{ $r->nama_petugas }}
                        </div>
                    </div>
                    <div class="score-display-box" title="Nilai Setoran: {{ $r->nilai }} ({{ $pred['desc'] }})">
                        <span class="score-val">{{ $r->nilai }}</span>
                        <span class="score-predikat {{ $pred['class'] }}" style="padding: 1px 6px; border-radius: 4px;">{{ $pred['label'] }}</span>
                    </div>
                </article>
            @empty
                <div class="empty-state">
                    <h3>Belum ada riwayat laporan</h3>
                    <p>Laporan setoran hafalan yang berhasil disimpan akan langsung ditampilkan di sini.</p>
                </div>
            @endforelse
        </div>
</div>

<!-- Modal Mushaf Digital & Penjelajah Surah (API Al-Qur'an) -->
<div class="modal-overlay" id="quran-modal" role="dialog" aria-modal="true" aria-labelledby="quran-modal-title">
    <div class="modal-quran-container">
        <!-- Header Modal -->
        <div class="quran-modal-header">
            <div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 34px; height: 34px; border-radius: 9px; background: rgba(255,255,255,0.15); display: grid; place-items: center; border: 1.5px solid var(--gold-border);">
                        <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="#fce8a9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                            <path d="M6 6h10"/>
                            <path d="M6 10h10"/>
                        </svg>
                    </div>
                    <h2 class="modal-title" id="quran-modal-title" style="color: #ffffff; margin: 0; font-size: 20px;">
                        Mushaf &amp; Rujukan Surah Al-Qur'an
                    </h2>
                </div>
                <small style="color: rgba(255,255,255,0.85); font-size: 12.5px; margin-top: 3px; display: block;">
                    Didukung API Al-Qur'an Kemenag RI / EQuran.id &middot; Lengkap 114 Surah, Teks Arab, Terjemahan &amp; Audio Murottal
                </small>
            </div>
            <button type="button" id="btn-close-quran-modal" style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.3); color: #ffffff; width: 34px; height: 34px; border-radius: 8px; font-size: 20px; cursor: pointer; display: grid; place-items: center;" aria-label="Tutup Mushaf">&times;</button>
        </div>

        <!-- Body Modal -->
        <div class="quran-modal-body">
            <!-- Tampilan 1: Daftar Seluruh Surah -->
            <div id="quran-view-surah-list">
                <div class="quran-search-bar">
                    <div style="flex: 1; position: relative; min-width: 220px;">
                        <svg class="svg-icon" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--ink-muted);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="search" 
                               id="quran-search-input" 
                               class="form-input" 
                               style="padding-left: 42px; background: #ffffff;" 
                               placeholder="Cari nama surah atau nomor (mis. Al-Kahfi, Yasin, 18, 36...)">
                    </div>
                </div>

                <div class="quran-filter-chips">
                    <button type="button" class="quran-chip active" data-filter="all">Semua Surah (114)</button>
                    <button type="button" class="quran-chip" data-filter="juz30">Juz 'Amma (Surah 78-114)</button>
                    <button type="button" class="quran-chip" data-filter="Mekah">Makkiyyah</button>
                    <button type="button" class="quran-chip" data-filter="Madinah">Madaniyyah</button>
                </div>

                <!-- State: Loading API -->
                <div id="quran-loading-surah" style="text-align: center; padding: 48px 20px;">
                    <div style="display: inline-block; width: 36px; height: 36px; border: 3px solid rgba(6, 84, 67, 0.2); border-top-color: var(--pri-green); border-radius: 50%; animation: spin 0.8s linear infinite;"></div>
                    <p style="margin-top: 14px; color: var(--ink-muted); font-size: 14px;">Menghubungkan ke API Al-Qur'an dan memuat 114 surah...</p>
                </div>

                <!-- State: Error API -->
                <div id="quran-error-surah" class="alert-box alert-danger" style="display: none;">
                    <div>
                        <strong>Gagal menghubungi API Al-Qur'an eksternal.</strong>
                        <p style="margin: 4px 0 0; font-size: 13px;">Sistem beralih menggunakan daftar 114 surah lokal.</p>
                    </div>
                    <button type="button" class="btn-secondary" id="btn-retry-quran" style="padding: 6px 12px; font-size: 12.5px;">Coba Ulang</button>
                </div>

                <!-- Grid Kartu Surah -->
                <div class="quran-surah-grid" id="quran-surah-container"></div>

                <!-- State: Kosong / Pencarian Tidak Ditemukan -->
                <div id="quran-empty-search" class="empty-state" style="display: none; margin-top: 20px;">
                    <h3>Surah Tidak Ditemukan</h3>
                    <p>Tidak ada surah yang cocok dengan kata kunci pencarian Anda.</p>
                </div>
            </div>

            <!-- Tampilan 2: Pembaca Ayat Per Surah -->
            <div id="quran-view-ayah-reader" style="display: none;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; gap: 10px; flex-wrap: wrap;">
                    <button type="button" class="btn-secondary" id="btn-back-to-surahs" style="display: inline-flex; align-items: center; gap: 6px;">
                        <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"/>
                            <polyline points="12 19 5 12 12 5"/>
                        </svg>
                        <span>Kembali ke Daftar Surah</span>
                    </button>

                    <button type="button" class="btn-primary" id="btn-choose-this-surah" style="padding: 7px 16px;">
                        <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <span>Pilih Surah Ini untuk Setoran</span>
                    </button>
                </div>

                <!-- Banner Informasi Detail Surah -->
                <div style="background: linear-gradient(135deg, #065443 0%, #033c30 100%); color: #ffffff; border-radius: 16px; padding: 22px 24px; margin-bottom: 22px; border: 1.5px solid var(--gold-border); text-align: center; position: relative;">
                    <div id="ayah-surah-arabic" style="font-family: 'Amiri', serif; font-size: 38px; color: var(--gold-border); margin-bottom: 6px; direction: rtl;"></div>
                    <h3 id="ayah-surah-title" style="margin: 0; font-size: 22px; color: #ffffff; font-family: 'Fraunces', serif;"></h3>
                    <p id="ayah-surah-meta" style="margin: 6px 0 14px; font-size: 13.5px; color: #fdf5df;"></p>

                    <!-- Audio Player Murottal API -->
                    <div id="ayah-audio-container" style="display: inline-flex; align-items: center; gap: 10px; background: rgba(0,0,0,0.25); padding: 8px 16px; border-radius: 30px; border: 1px solid rgba(223, 200, 136, 0.4);">
                        <button type="button" id="btn-toggle-surah-audio" style="background: var(--gold-primary); color: #04362b; border: none; width: 32px; height: 32px; border-radius: 50%; display: grid; place-items: center; cursor: pointer;" title="Putar Murottal Audio">
                            <svg class="svg-icon-sm" id="icon-play-audio" viewBox="0 0 24 24" fill="currentColor">
                                <polygon points="5 3 19 12 5 21 5 3"/>
                            </svg>
                        </button>
                        <span id="label-audio-surah" style="font-size: 12.5px; font-weight: 600; color: #fce8a9;">Murottal Audio Surah</span>
                        <audio id="quran-audio-element" preload="none"></audio>
                    </div>
                </div>

                <!-- Loading Ayat -->
                <div id="quran-loading-ayahs" style="text-align: center; padding: 40px 20px;">
                    <div style="display: inline-block; width: 32px; height: 32px; border: 3px solid rgba(6, 84, 67, 0.2); border-top-color: var(--pri-green); border-radius: 50%; animation: spin 0.8s linear infinite;"></div>
                    <p style="margin-top: 12px; color: var(--ink-muted); font-size: 14px;">Memuat ayat-ayat dari API Al-Qur'an...</p>
                </div>

                <!-- Error Ayat -->
                <div id="quran-error-ayahs" class="alert-box alert-danger" style="display: none;">
                    <span>Gagal memuat ayat dari server API.</span>
                    <button type="button" class="btn-secondary" id="btn-retry-ayahs">Coba Lagi</button>
                </div>

                <!-- Daftar Ayat -->
                <div id="quran-ayahs-container"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kelasSelect = document.getElementById('filter_kelas');
        const siswaSelect = document.getElementById('siswa_id');
        const setoranForm = document.getElementById('form-catat-setoran');
        const submitBtn = document.getElementById('btn-submit-setoran');
        const nilaiInput = document.getElementById('nilai');
        const evalCard = document.getElementById('eval-live-card');
        const evalBadge = document.getElementById('eval-predikat-badge');
        const evalDesc = document.getElementById('eval-predikat-desc');

        // Evaluasi Nilai Interaktif Real-Time
        function updateLiveEvaluation(val) {
            if (!evalCard || val === '' || isNaN(val)) {
                if (evalCard) evalCard.style.display = 'none';
                return;
            }
            const n = parseInt(val, 10);
            if (n < 0 || n > 100) {
                evalCard.style.display = 'none';
                return;
            }
            evalCard.style.display = 'flex';
            evalBadge.className = 'predikat-badge';
            if (n >= 90) {
                evalBadge.classList.add('predikat-mumtaz');
                evalBadge.textContent = 'Mumtaz';
                evalDesc.textContent = 'Istimewa (90 s.d. 100)';
            } else if (n >= 80) {
                evalBadge.classList.add('predikat-jayyid-jiddan');
                evalBadge.textContent = 'Jayyid Jiddan';
                evalDesc.textContent = 'Sangat Baik (80 s.d. 89)';
            } else if (n >= 70) {
                evalBadge.classList.add('predikat-jayyid');
                evalBadge.textContent = 'Jayyid';
                evalDesc.textContent = 'Baik (70 s.d. 79)';
            } else if (n >= 60) {
                evalBadge.classList.add('predikat-maqbul');
                evalBadge.textContent = 'Maqbul';
                evalDesc.textContent = 'Cukup (60 s.d. 69)';
            } else {
                evalBadge.classList.add('predikat-dhaif');
                evalBadge.textContent = 'Dhaif';
                evalDesc.textContent = 'Perlu Bimbingan (< 60)';
            }
        }

        if (nilaiInput) {
            nilaiInput.addEventListener('input', () => updateLiveEvaluation(nilaiInput.value));
            if (nilaiInput.value) updateLiveEvaluation(nilaiInput.value);
        }

        // Pilihan Siswa Dinamis Berdasarkan Kelas
        if (kelasSelect && siswaSelect) {
            kelasSelect.addEventListener('change', function() {
                const selectedKls = this.value;
                const options = siswaSelect.querySelectorAll('option');

                options.forEach(opt => {
                    if (opt.value === '') return;
                    const optKelas = opt.getAttribute('data-kelas');
                    if (!selectedKls || optKelas === selectedKls) {
                        opt.style.display = '';
                    } else {
                        opt.style.display = 'none';
                    }
                });

                const currentSelected = siswaSelect.selectedOptions[0];
                if (currentSelected && currentSelected.style.display === 'none') {
                    siswaSelect.value = '';
                }
            });
        }

        // AJAX Form Submission untuk UX Instan
        if (setoranForm) {
            setoranForm.addEventListener('submit', function(e) {
                if (!setoranForm.checkValidity()) return;

                e.preventDefault();
                submitBtn.disabled = true;
                const originalHtml = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span>Menyimpan Laporan...</span>';

                const formData = new FormData(setoranForm);

                fetch("{{ route('petugas.store') }}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(res => {
                    if (res.success) {
                        window.showToast(res.message);
                        
                        // Reset input siswa, ayat, dan nilai
                        document.getElementById('ayat').value = '';
                        document.getElementById('nilai').value = '';
                        if (evalCard) evalCard.style.display = 'none';
                        siswaSelect.value = '';
                        
                        // Predikat hasil untuk kartu baru
                        const n = parseInt(res.data.nilai, 10);
                        let predClass = 'predikat-dhaif';
                        let predLabel = 'Dhaif';
                        if (n >= 90) { predClass = 'predikat-mumtaz'; predLabel = 'Mumtaz'; }
                        else if (n >= 80) { predClass = 'predikat-jayyid-jiddan'; predLabel = 'Jayyid Jiddan'; }
                        else if (n >= 70) { predClass = 'predikat-jayyid'; predLabel = 'Jayyid'; }
                        else if (n >= 60) { predClass = 'predikat-maqbul'; predLabel = 'Maqbul'; }

                        const container = document.getElementById('riwayat-list-container');
                        const emptyState = container.querySelector('.empty-state');
                        if (emptyState) emptyState.remove();

                        const newItem = document.createElement('article');
                        newItem.className = 'history-item';
                        newItem.innerHTML = `
                            <div class="student-avatar" title="${escapeHtml(res.data.nama_siswa)}">
                                ${escapeHtml(res.data.nama_siswa.charAt(0))}
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <div class="history-name">${escapeHtml(res.data.nama_siswa)}</div>
                                <div style="margin: 4px 0;">
                                    <span class="meta-tag">Kelas ${escapeHtml(res.data.kelas)}${res.data.jurusan ? ' ' + escapeHtml(res.data.jurusan) : ''}</span>
                                    <span class="meta-tag ${res.data.kategori === 'Ziyadah' ? 'tag-ziyadah' : 'tag-murojaah'}">${escapeHtml(res.data.kategori)}</span>
                                </div>
                                <div class="history-sub">
                                    <strong>${escapeHtml(res.data.surah)}</strong> &middot; Ayat ${escapeHtml(res.data.ayat)}<br>
                                    ${escapeHtml(res.data.waktu)} &middot; Disimak oleh ${escapeHtml(res.data.nama_petugas)}
                                </div>
                            </div>
                            <div class="score-display-box" title="Nilai Setoran">
                                <span class="score-val">${escapeHtml(res.data.nilai)}</span>
                                <span class="score-predikat ${predClass}" style="padding: 1px 6px; border-radius: 4px;">${predLabel}</span>
                            </div>
                        `;

                        container.insertBefore(newItem, container.firstChild);

                        const countEl = document.getElementById('riwayat-count');
                        if (countEl) {
                            const curCount = parseInt(countEl.textContent) || 0;
                            countEl.textContent = (curCount + 1) + ' laporan';
                        }
                    }
                })
                .catch(err => {
                    let errMsg = 'Terjadi kesalahan saat menyimpan laporan setoran.';
                    if (err && err.message) errMsg = err.message;
                    if (err && err.errors) {
                        const firstKey = Object.keys(err.errors)[0];
                        errMsg = err.errors[firstKey][0];
                    }
                    window.showToast(errMsg);
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHtml;
                });
            });
        }

        function escapeHtml(str) {
            return String(str ?? '').replace(/[&<>'"]/g, tag => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                "'": '&#39;',
                '"': '&quot;'
            }[tag] || tag));
        }

        // =========================================================================
        // Integrasi API Al-Qur'an (114 Surah, Teks Arab, Latin, Terjemahan & Audio)
        // =========================================================================
        const quranModal = document.getElementById('quran-modal');
        const openQuranBtn = document.getElementById('btn-open-quran-modal');
        const closeQuranBtn = document.getElementById('btn-close-quran-modal');
        const surahSelect = document.getElementById('surah');
        const ayatInput = document.getElementById('ayat');
        const ayatHint = document.getElementById('ayat-hint');
        const surahPreview = document.getElementById('quran-surah-preview');
        const previewReadAyahsBtn = document.getElementById('btn-preview-read-ayahs');

        const quranViewList = document.getElementById('quran-view-surah-list');
        const quranViewAyahs = document.getElementById('quran-view-ayah-reader');
        const quranLoadingSurah = document.getElementById('quran-loading-surah');
        const quranErrorSurah = document.getElementById('quran-error-surah');
        const quranSurahContainer = document.getElementById('quran-surah-container');
        const quranEmptySearch = document.getElementById('quran-empty-search');
        const quranSearchInput = document.getElementById('quran-search-input');
        const quranFilterChips = document.querySelectorAll('.quran-chip');

        const btnBackToSurahs = document.getElementById('btn-back-to-surahs');
        const btnChooseThisSurah = document.getElementById('btn-choose-this-surah');
        const quranLoadingAyahs = document.getElementById('quran-loading-ayahs');
        const quranErrorAyahs = document.getElementById('quran-error-ayahs');
        const quranAyahsContainer = document.getElementById('quran-ayahs-container');

        const ayahSurahArabic = document.getElementById('ayah-surah-arabic');
        const ayahSurahTitle = document.getElementById('ayah-surah-title');
        const ayahSurahMeta = document.getElementById('ayah-surah-meta');
        const quranAudio = document.getElementById('quran-audio-element');
        const btnToggleAudio = document.getElementById('btn-toggle-surah-audio');
        const iconPlayAudio = document.getElementById('icon-play-audio');
        const labelAudioSurah = document.getElementById('label-audio-surah');

        let surahDataList = [];
        let activeFilter = 'all';
        let currentViewingSurah = null;
        const cachedAyatData = {};

        // Fallback data nama surah dari backend PHP jika API eksternal offline
        const fallbackSurahList = @json($surahList);

        function initQuranFeature() {
            fetchSurahList();

            if (openQuranBtn) {
                openQuranBtn.addEventListener('click', () => {
                    openModal();
                });
            }

            if (closeQuranBtn) {
                closeQuranBtn.addEventListener('click', () => {
                    closeModal();
                });
            }

            if (quranModal) {
                quranModal.addEventListener('click', (e) => {
                    if (e.target === quranModal) closeModal();
                });
            }

            if (quranSearchInput) {
                quranSearchInput.addEventListener('input', filterAndRenderSurahs);
            }

            quranFilterChips.forEach(chip => {
                chip.addEventListener('click', function() {
                    quranFilterChips.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    activeFilter = this.getAttribute('data-filter');
                    filterAndRenderSurahs();
                });
            });

            if (btnBackToSurahs) {
                btnBackToSurahs.addEventListener('click', () => {
                    stopAudio();
                    quranViewList.style.display = 'block';
                    quranViewAyahs.style.display = 'none';
                });
            }

            if (btnChooseThisSurah) {
                btnChooseThisSurah.addEventListener('click', () => {
                    if (currentViewingSurah) {
                        applySurahSelection(currentViewingSurah);
                        closeModal();
                    }
                });
            }

            if (previewReadAyahsBtn) {
                previewReadAyahsBtn.addEventListener('click', () => {
                    const selVal = surahSelect.value;
                    const numMatch = selVal.match(/^(\d+)\./);
                    if (numMatch) {
                        openModal();
                        openAyahView(parseInt(numMatch[1], 10));
                    }
                });
            }

            if (surahSelect) {
                surahSelect.addEventListener('change', function() {
                    updateSurahPreviewFromSelection(this.value);
                });
                if (surahSelect.value) {
                    updateSurahPreviewFromSelection(surahSelect.value);
                }
            }

            const btnRetry = document.getElementById('btn-retry-quran');
            if (btnRetry) btnRetry.addEventListener('click', fetchSurahList);

            const btnRetryAyah = document.getElementById('btn-retry-ayahs');
            if (btnRetryAyah) {
                btnRetryAyah.addEventListener('click', () => {
                    if (currentViewingSurah) openAyahView(currentViewingSurah.nomor);
                });
            }

            if (btnToggleAudio && quranAudio) {
                btnToggleAudio.addEventListener('click', toggleAudioPlayback);
                quranAudio.addEventListener('ended', () => {
                    setAudioIconPlaying(false);
                });
                quranAudio.addEventListener('pause', () => {
                    setAudioIconPlaying(false);
                });
                quranAudio.addEventListener('play', () => {
                    setAudioIconPlaying(true);
                });
            }
        }

        function openModal() {
            if (!quranModal) return;
            quranModal.classList.add('active');
            if (quranViewAyahs.style.display !== 'block') {
                quranViewList.style.display = 'block';
                if (quranSearchInput) quranSearchInput.focus();
            }
        }

        function closeModal() {
            if (!quranModal) return;
            quranModal.classList.remove('active');
            stopAudio();
        }

        function stopAudio() {
            if (quranAudio) {
                quranAudio.pause();
                quranAudio.currentTime = 0;
            }
            setAudioIconPlaying(false);
        }

        function toggleAudioPlayback() {
            if (!quranAudio || !quranAudio.src) return;
            if (quranAudio.paused) {
                quranAudio.play().catch(e => {
                    window.showToast('Gagal memutar audio: ' + e.message);
                });
            } else {
                quranAudio.pause();
            }
        }

        function setAudioIconPlaying(isPlaying) {
            if (!iconPlayAudio) return;
            if (isPlaying) {
                iconPlayAudio.innerHTML = `
                    <rect x="6" y="4" width="4" height="16"/>
                    <rect x="14" y="4" width="4" height="16"/>
                `;
                if (labelAudioSurah) labelAudioSurah.textContent = 'Jeda Murottal';
            } else {
                iconPlayAudio.innerHTML = `
                    <polygon points="5 3 19 12 5 21 5 3"/>
                `;
                if (labelAudioSurah) labelAudioSurah.textContent = 'Murottal Audio Surah';
            }
        }

        function fetchSurahList() {
            if (quranLoadingSurah) quranLoadingSurah.style.display = 'block';
            if (quranErrorSurah) quranErrorSurah.style.display = 'none';

            fetch('https://equran.id/api/v2/surat')
                .then(res => {
                    if (!res.ok) throw new Error('Status respons: ' + res.status);
                    return res.json();
                })
                .then(result => {
                    if (result && result.data) {
                        surahDataList = result.data;
                        if (quranLoadingSurah) quranLoadingSurah.style.display = 'none';
                        filterAndRenderSurahs();
                        if (surahSelect && surahSelect.value) {
                            updateSurahPreviewFromSelection(surahSelect.value);
                        }
                    } else {
                        throw new Error('Data surat tidak ditemukan');
                    }
                })
                .catch(err => {
                    console.warn('API equran.id tidak dapat dijangkau, menggunakan fallback data lokal:', err);
                    if (quranLoadingSurah) quranLoadingSurah.style.display = 'none';
                    if (quranErrorSurah) quranErrorSurah.style.display = 'flex';

                    // Bangun surah data dari fallback PHP list
                    surahDataList = fallbackSurahList.map((item, idx) => {
                        const parts = item.split('. ');
                        const num = parseInt(parts[0], 10);
                        const name = parts[1] || item;
                        return {
                            nomor: num,
                            namaLatin: name,
                            nama: 'القرآن',
                            jumlahAyat: 0,
                            tempatTurun: num >= 78 ? 'Mekah' : 'Madinah',
                            arti: 'Surah ke-' + num,
                            audioFull: {}
                        };
                    });
                    filterAndRenderSurahs();
                });
        }

        function filterAndRenderSurahs() {
            if (!quranSurahContainer) return;
            const query = (quranSearchInput ? quranSearchInput.value : '').trim().toLowerCase();

            const filtered = surahDataList.filter(s => {
                // Filter kategori tab
                if (activeFilter === 'juz30' && (s.nomor < 78 || s.nomor > 114)) return false;
                if (activeFilter === 'Mekah' && s.tempatTurun !== 'Mekah') return false;
                if (activeFilter === 'Madinah' && s.tempatTurun !== 'Madinah') return false;

                // Filter pencarian
                if (query) {
                    const latinMatch = (s.namaLatin || '').toLowerCase().includes(query);
                    const numMatch = String(s.nomor) === query;
                    const artiMatch = (s.arti || '').toLowerCase().includes(query);
                    const arabMatch = (s.nama || '').includes(query);
                    return latinMatch || numMatch || artiMatch || arabMatch;
                }
                return true;
            });

            quranSurahContainer.innerHTML = '';
            if (filtered.length === 0) {
                if (quranEmptySearch) quranEmptySearch.style.display = 'block';
                return;
            }
            if (quranEmptySearch) quranEmptySearch.style.display = 'none';

            filtered.forEach(surah => {
                const card = document.createElement('div');
                card.className = 'quran-surah-card';
                card.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 12px; min-width: 0;">
                        <span class="quran-num-badge">${surah.nomor}</span>
                        <div style="min-width: 0;">
                            <div style="font-weight: 700; color: var(--pri-green); font-size: 14.5px;">${escapeHtml(surah.namaLatin)}</div>
                            <div style="font-size: 12px; color: var(--ink-muted); margin-top: 2px;">
                                ${escapeHtml(surah.arti || '')} ${surah.jumlahAyat ? '&middot; ' + surah.jumlahAyat + ' Ayat' : ''}
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 6px; flex-shrink: 0;">
                        <span class="quran-arabic-title" style="font-size: 20px;">${escapeHtml(surah.nama || '')}</span>
                        <div style="display: flex; gap: 6px;">
                            <button type="button" class="btn-secondary btn-select-surah" style="padding: 3px 8px; font-size: 11px;" title="Pilih surah ini untuk setoran">
                                Pilih
                            </button>
                            <button type="button" class="btn-primary btn-read-surah" style="padding: 3px 8px; font-size: 11px;" title="Baca ayat surah">
                                Baca
                            </button>
                        </div>
                    </div>
                `;

                card.querySelector('.btn-select-surah').addEventListener('click', (e) => {
                    e.stopPropagation();
                    applySurahSelection(surah);
                    closeModal();
                });

                card.querySelector('.btn-read-surah').addEventListener('click', (e) => {
                    e.stopPropagation();
                    openAyahView(surah.nomor);
                });

                card.addEventListener('click', () => {
                    openAyahView(surah.nomor);
                });

                quranSurahContainer.appendChild(card);
            });
        }

        function openAyahView(surahNumber) {
            currentViewingSurah = surahDataList.find(s => s.nomor === surahNumber);
            quranViewList.style.display = 'none';
            quranViewAyahs.style.display = 'block';

            if (ayahSurahArabic) ayahSurahArabic.textContent = currentViewingSurah ? currentViewingSurah.nama : '';
            if (ayahSurahTitle) {
                ayahSurahTitle.textContent = currentViewingSurah 
                    ? `Surah ${currentViewingSurah.namaLatin} (${currentViewingSurah.nomor})` 
                    : `Surah ${surahNumber}`;
            }
            if (ayahSurahMeta) {
                ayahSurahMeta.textContent = currentViewingSurah 
                    ? `Arti: ${currentViewingSurah.arti || '-'} &middot; Golongan: ${currentViewingSurah.tempatTurun || '-'} &middot; Total: ${currentViewingSurah.jumlahAyat || '-'} Ayat` 
                    : '';
            }

            // Atur audio player surah
            if (quranAudio && currentViewingSurah && currentViewingSurah.audioFull) {
                const audioUrl = currentViewingSurah.audioFull['05'] || currentViewingSurah.audioFull['01'] || '';
                quranAudio.src = audioUrl;
                setAudioIconPlaying(false);
            }

            // Muat daftar ayat dari API
            loadAyahs(surahNumber);
        }

        function loadAyahs(surahNumber) {
            if (quranLoadingAyahs) quranLoadingAyahs.style.display = 'block';
            if (quranErrorAyahs) quranErrorAyahs.style.display = 'none';
            if (quranAyahsContainer) quranAyahsContainer.innerHTML = '';

            if (cachedAyatData[surahNumber]) {
                if (quranLoadingAyahs) quranLoadingAyahs.style.display = 'none';
                renderAyahs(cachedAyatData[surahNumber]);
                return;
            }

            fetch(`https://equran.id/api/v2/surat/${surahNumber}`)
                .then(res => {
                    if (!res.ok) throw new Error('Status: ' + res.status);
                    return res.json();
                })
                .then(res => {
                    if (res && res.data && res.data.ayat) {
                        cachedAyatData[surahNumber] = res.data;
                        if (quranLoadingAyahs) quranLoadingAyahs.style.display = 'none';
                        renderAyahs(res.data);
                    } else {
                        throw new Error('Data ayat tidak valid');
                    }
                })
                .catch(err => {
                    console.error('Gagal mengambil ayat:', err);
                    if (quranLoadingAyahs) quranLoadingAyahs.style.display = 'none';
                    if (quranErrorAyahs) quranErrorAyahs.style.display = 'flex';
                });
        }

        function renderAyahs(data) {
            if (!quranAyahsContainer) return;
            quranAyahsContainer.innerHTML = '';

            const ayahs = data.ayat || [];
            ayahs.forEach(ayah => {
                const item = document.createElement('article');
                item.className = 'quran-ayah-item';
                item.innerHTML = `
                    <div class="quran-ayah-header">
                        <span class="quran-num-badge" style="width: 32px; height: 32px; font-size: 12.5px;">${ayah.nomorAyat}</span>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            ${ayah.audio && ayah.audio['05'] ? `
                                <button type="button" class="btn-secondary btn-play-single-ayah" style="padding: 4px 8px; font-size: 11px;" data-audio="${ayah.audio['05']}" title="Putar audio ayat ${ayah.nomorAyat}">
                                    Dengarkan Ayat
                                </button>
                            ` : ''}
                        </div>
                    </div>
                    <div class="quran-arabic-text">${escapeHtml(ayah.teksArab)}</div>
                    <div class="quran-latin-text">${escapeHtml(ayah.teksLatin)}</div>
                    <div class="quran-translation-text">${escapeHtml(ayah.teksIndonesia)}</div>
                `;

                const btnPlayAyah = item.querySelector('.btn-play-single-ayah');
                if (btnPlayAyah) {
                    btnPlayAyah.addEventListener('click', function() {
                        const audioSrc = this.getAttribute('data-audio');
                        if (quranAudio && audioSrc) {
                            quranAudio.src = audioSrc;
                            quranAudio.play();
                            setAudioIconPlaying(true);
                            if (labelAudioSurah) labelAudioSurah.textContent = `Memutar Ayat ${ayah.nomorAyat}`;
                        }
                    });
                }

                quranAyahsContainer.appendChild(item);
            });
        }

        function applySurahSelection(surah) {
            if (!surahSelect) return;
            // Format dropdown: "1. Al-Fatihah"
            const expectedPrefix = `${surah.nomor}.`;
            let matchedOption = null;

            for (const opt of surahSelect.options) {
                if (opt.value && opt.value.startsWith(expectedPrefix)) {
                    matchedOption = opt;
                    break;
                }
            }

            if (matchedOption) {
                surahSelect.value = matchedOption.value;
            } else {
                surahSelect.value = `${surah.nomor}. ${surah.namaLatin}`;
            }

            updateSurahPreviewFromSelection(surahSelect.value);

            if (ayatInput && surah.jumlahAyat) {
                ayatInput.placeholder = `mis. 1-${surah.jumlahAyat}`;
                if (!ayatInput.value) {
                    ayatInput.value = `1-${surah.jumlahAyat}`;
                }
            }

            if (ayatHint && surah.jumlahAyat) {
                ayatHint.textContent = `Surah ${surah.namaLatin} memiliki total ${surah.jumlahAyat} ayat.`;
            }

            window.showToast(`Surah ${surah.namaLatin} telah dipilih.`);
            if (ayatInput) ayatInput.focus();
        }

        function updateSurahPreviewFromSelection(value) {
            if (!surahPreview) return;
            if (!value) {
                surahPreview.style.display = 'none';
                return;
            }

            const match = value.match(/^(\d+)\.\s*(.+)$/);
            if (!match) {
                surahPreview.style.display = 'none';
                return;
            }

            const num = parseInt(match[1], 10);
            const latinName = match[2];

            const surah = surahDataList.find(s => s.nomor === num);

            document.getElementById('preview-surah-num').textContent = num;
            document.getElementById('preview-surah-latin').textContent = latinName;
            document.getElementById('preview-surah-arabic').textContent = surah ? surah.nama : 'القرآن';
            document.getElementById('preview-surah-type').textContent = surah ? surah.tempatTurun : (num >= 78 ? 'Mekah' : 'Madinah');
            document.getElementById('preview-surah-arti').textContent = surah ? (surah.arti || '-') : '-';
            document.getElementById('preview-surah-total').textContent = surah && surah.jumlahAyat ? surah.jumlahAyat : '-';

            if (ayatHint && surah && surah.jumlahAyat) {
                ayatHint.textContent = `Surah ${latinName} terdiri dari ${surah.jumlahAyat} ayat.`;
            }

            surahPreview.style.display = 'flex';
        }

        // Mulai inisialisasi Al-Qur'an
        initQuranFeature();
    });
</script>
@endsection
