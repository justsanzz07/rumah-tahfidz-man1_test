@extends('layouts.app')

@section('title', 'Kelola Data Petugas')

@section('content')
<div class="ui-card gold-edge">
    <!-- Nav Tabs Menu Administrator -->
    <nav class="nav-tabs" aria-label="Menu Administrator">
        <a href="{{ route('admin.dashboard') }}" class="tab-btn">
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
        <a href="{{ route('admin.petugas.index') }}" class="tab-btn active">
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

    <!-- Welcome & Banner Ringkasan Pembimbing -->
    <div class="admin-welcome-banner">
        <div class="admin-welcome-text">
            <div class="admin-welcome-eyebrow">Manajemen Tenaga Pendidik &middot; MAN 1 Probolinggo</div>
            <h2 class="admin-welcome-heading">Ustadz & Petugas Penyimak Tahfidz</h2>
            <p class="admin-welcome-desc">Kelola nama ustadz/ustadzah pembimbing, pantau volume simakan hafalan, evaluasi riwayat setoran, serta atur status keaktifan penugasan.</p>
        </div>
        <div class="admin-datetime-card">
            <div class="datetime-card-header">
                <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                </svg>
                <span>Status Tim Penyimak</span>
            </div>
            <div class="datetime-card-day" style="font-size: 26px; font-weight: 700; color: #ffffff;">{{ $totalAktif }} <small style="font-size: 14px; font-weight: normal; opacity: 0.9;">Aktif</small></div>
            <div class="datetime-card-date" style="opacity: 0.9;">dari total {{ $totalPetugas }} Petugas Terdaftar</div>
            <div class="datetime-card-time" style="font-size: 13px; font-weight: 500; margin-top: 4px; color: var(--gold-light);">
                {{ $totalSetoranSemua }} Total Setoran Disimak
            </div>
        </div>
    </div>

    <!-- Panel Tambah Petugas Baru -->
    <div style="max-width: 680px; margin-bottom: 28px;">
        <div class="ui-card" style="box-shadow: var(--shadow-subtle); border-color: var(--border-line); background: #fafcfb;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                <svg class="svg-icon-sm" style="color: var(--pri-green);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <line x1="19" y1="8" x2="19" y2="14"/>
                    <line x1="22" y1="11" x2="16" y2="11"/>
                </svg>
                <h3 style="margin: 0; font-size: 17px; font-weight: 700;">Tambah Petugas Baru</h3>
            </div>
            <p class="card-desc" style="margin-bottom: 14px;">Nama ustadz atau ustadzah baru akan langsung tersedia dalam daftar pilihan pada formulir setoran santri.</p>

            <form action="{{ route('admin.petugas.store') }}" method="POST">
                @csrf
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="nama-petugas-input" class="form-label">Nama Lengkap Petugas / Ustadz / Ustadzah</label>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <input type="text" 
                               id="nama-petugas-input" 
                               name="nama" 
                               class="form-input" 
                               style="flex: 1; min-width: 240px;"
                               placeholder="mis. Ustadz Ahmad Dahlan, S.Pd.I" 
                               required>
                        <button type="submit" class="btn-primary" style="flex-shrink: 0;">
                            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="16"/>
                                <line x1="8" y1="12" x2="16" y2="12"/>
                            </svg>
                            <span>Tambah Petugas</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Toolbar Pencarian & Filter Petugas -->
    <div class="admin-search-toolbar">
        <div class="admin-search-input-wrap">
            <svg class="svg-icon-sm admin-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="11" cy="11" r="8"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="search" 
                   id="search-petugas-input" 
                   class="admin-search-input" 
                   placeholder="Cari nama ustadz / ustadzah..." 
                   aria-label="Cari nama petugas">
        </div>

        <div class="admin-filter-pills" role="tablist" aria-label="Filter status petugas">
            <button type="button" class="admin-filter-pill-btn active" data-filter="all" onclick="filterPetugasStatus('all', this)">
                Semua ({{ $totalPetugas }})
            </button>
            <button type="button" class="admin-filter-pill-btn" data-filter="active" onclick="filterPetugasStatus('active', this)">
                Aktif ({{ $totalAktif }})
            </button>
            <button type="button" class="admin-filter-pill-btn" data-filter="inactive" onclick="filterPetugasStatus('inactive', this)">
                Nonaktif ({{ $totalNonaktif }})
            </button>
        </div>
    </div>

    <!-- Grid Kartu Petugas yang Rapi -->
    <div class="petugas-card-grid" id="petugas-grid-container">
        @forelse ($petugasList as $p)
            @php
                $avgScore = $p->laporan_setorans_avg_nilai ? round((float) $p->laporan_setorans_avg_nilai, 1) : null;
                $initial = mb_substr($p->nama, 0, 1);
                $latest = $p->latest_setoran;
            @endphp
            <div class="petugas-card {{ !$p->is_active ? 'is-inactive' : '' }}" 
                 data-nama="{{ strtolower($p->nama) }}" 
                 data-status="{{ $p->is_active ? 'active' : 'inactive' }}">
                
                <div>
                    <!-- Header Kartu: Avatar, Nama, Status -->
                    <div class="petugas-card-header">
                        <div class="petugas-avatar-wrap">
                            <div class="petugas-avatar" aria-hidden="true">
                                {{ $initial }}
                            </div>
                            <div class="petugas-name-box">
                                <h3 class="petugas-name">{{ $p->nama }}</h3>
                                <span class="petugas-role">Penyimak Tahfidz Al-Qur'an</span>
                            </div>
                        </div>

                        <div>
                            @if ($p->is_active)
                                <span class="badge-status-active" title="Petugas aktif menyimak">
                                    <span class="badge-dot" aria-hidden="true"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="badge-status-inactive" title="Petugas dinonaktifkan (arsip)">
                                    Nonaktif
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Ringkasan Metrik Angka -->
                    <div class="petugas-metrics-grid">
                        <div class="petugas-metric-item">
                            <span class="petugas-metric-val">{{ $p->laporan_setorans_count }}</span>
                            <span class="petugas-metric-lbl">Setoran</span>
                        </div>
                        <div class="petugas-metric-item">
                            <span class="petugas-metric-val">{{ $p->total_santri }}</span>
                            <span class="petugas-metric-lbl">Santri Dibina</span>
                        </div>
                        <div class="petugas-metric-item">
                            <span class="petugas-metric-val">{{ $avgScore ?: '-' }}</span>
                            <span class="petugas-metric-lbl">Rerata Nilai</span>
                        </div>
                    </div>

                    <!-- Highlight Simakan Terakhir -->
                    <div class="petugas-latest-box">
                        <svg class="svg-icon-sm petugas-latest-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <div style="min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            @if ($latest)
                                <strong>{{ $latest->nama_siswa }}</strong> &middot; {{ $latest->surah }} ({{ $latest->waktu_setoran->format('d/m') }})
                            @else
                                <em>Belum ada catatan setoran</em>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer Aksi Kartu: Riwayat, Edit, Hapus -->
                <div class="petugas-actions">
                    <div class="petugas-actions-primary">
                        <button type="button" 
                                class="btn-secondary btn-sm" 
                                style="width: 100%; justify-content: center; gap: 6px;"
                                onclick="openRiwayatPetugasModal({
                                    id: '{{ $p->id }}',
                                    nama: '{{ addslashes($p->nama) }}',
                                    riwayatUrl: '{{ route('admin.petugas.riwayat', $p) }}',
                                    isActive: {{ $p->is_active ? 'true' : 'false' }}
                                })"
                                title="Lihat riwayat penyimakan hafalan oleh {{ $p->nama }}">
                            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                            </svg>
                            <span>Riwayat Penyimakan</span>
                        </button>
                    </div>

                    <div class="petugas-actions-secondary">
                        <button type="button" 
                                class="btn-secondary btn-sm"
                                onclick="openEditPetugasModal({
                                    id: '{{ $p->id }}',
                                    nama: '{{ addslashes($p->nama) }}',
                                    isActive: {{ $p->is_active ? 'true' : 'false' }},
                                    updateUrl: '{{ route('admin.petugas.update', $p) }}'
                                })"
                                title="Perbarui data ustadz / ustadzah"
                                aria-label="Edit petugas {{ $p->nama }}">
                            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            <span>Edit</span>
                        </button>

                        <button type="button" 
                                class="btn-danger btn-sm"
                                onclick="openConfirmModal({
                                    title: 'Hapus Petugas Penyimak',
                                    message: 'Apakah Anda yakin ingin menghapus petugas {{ addslashes($p->nama) }}? Terdapat {{ $p->laporan_setorans_count }} riwayat setoran siswa yang pernah disimak. Data setoran siswa akan tetap aman tersimpan dalam arsip.',
                                    actionUrl: '{{ route('admin.petugas.destroy', $p) }}',
                                    method: 'DELETE',
                                    confirmButtonText: 'Ya, Hapus Petugas'
                                })"
                                title="Hapus petugas dari sistem"
                                aria-label="Hapus petugas {{ $p->nama }}">
                            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            </svg>
                            <span>Hapus</span>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state" style="grid-column: 1 / -1;">
                <h3>Belum ada petugas terdaftar</h3>
                <p>Tambahkan petugas penyimak menggunakan formulir di atas.</p>
            </div>
        @endforelse
    </div>

    <!-- Indikator Pencarian Nihil -->
    <div id="no-search-results" class="empty-state" style="display: none; margin-top: 24px;">
        <h3>Petugas tidak ditemukan</h3>
        <p>Tidak ada ustadz atau ustadzah yang sesuai dengan kata kunci pencarian Anda.</p>
    </div>
</div>

<!-- Modal Edit Petugas -->
<div class="modal-overlay" id="edit-petugas-modal" role="dialog" aria-modal="true" aria-labelledby="edit-petugas-modal-title">
    <div class="modal-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h2 class="modal-title" id="edit-petugas-modal-title" style="margin: 0;">Edit Data Petugas</h2>
            <button type="button" class="btn-modal-close" id="btn-close-edit-icon" aria-label="Tutup modal">&times;</button>
        </div>
        
        <form id="edit-petugas-form" method="POST" action="">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="edit-petugas-nama" class="form-label">Nama Lengkap Ustadz / Ustadzah</label>
                <input type="text" id="edit-petugas-nama" name="nama" class="form-input" required>
            </div>

            <div class="form-group" style="margin-top: 14px;">
                <label class="form-label">Status Penugasan</label>
                <div style="display: flex; gap: 16px; margin-top: 8px;">
                    <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px;">
                        <input type="radio" name="is_active" id="edit-petugas-status-aktif" value="1">
                        <span>Aktif (Dapat dipilih saat setoran)</span>
                    </label>
                    <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px;">
                        <input type="radio" name="is_active" id="edit-petugas-status-nonaktif" value="0">
                        <span>Nonaktif (Arsip)</span>
                    </label>
                </div>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 24px;">
                <button type="button" class="btn-secondary" id="btn-close-edit-petugas">Batal</button>
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Riwayat Penyimakan Petugas (Slide-over / Large Dialog) -->
<div class="modal-overlay" id="riwayat-petugas-modal" role="dialog" aria-modal="true" aria-labelledby="riwayat-modal-title">
    <div class="modal-container modal-lg">
        <div class="modal-header-bar">
            <div>
                <h2 class="modal-title" id="riwayat-modal-title" style="margin: 0; font-size: 20px;">Riwayat Penyimakan</h2>
                <div id="riwayat-modal-sub" style="font-size: 13px; color: var(--ink-muted); margin-top: 2px;">
                    Memuat catatan mutaba'ah siswa...
                </div>
            </div>
            <button type="button" class="btn-modal-close" id="btn-close-riwayat-icon" aria-label="Tutup modal riwayat">&times;</button>
        </div>

        <div class="modal-body-scroll">
            <!-- Loading Indicator -->
            <div id="riwayat-loading" style="text-align: center; padding: 40px 20px;">
                <div style="display: inline-block; width: 36px; height: 36px; border: 3.5px solid #dbe5e0; border-top-color: var(--pri-green); border-radius: 50%; animation: spin 0.8s linear infinite;"></div>
                <p style="margin-top: 12px; color: var(--ink-muted); font-size: 14px;">Mengambil data riwayat setoran dari sistem...</p>
            </div>

            <!-- Content Container -->
            <div id="riwayat-content" style="display: none;">
                <!-- Summary Chips -->
                <div class="stats-row" style="margin-bottom: 20px;">
                    <div class="stat-box" style="padding: 12px 14px;">
                        <div>
                            <b id="riwayat-stat-total" style="font-size: 22px;">0</b>
                            <span style="font-size: 12px;">Total Setoran</span>
                        </div>
                    </div>
                    <div class="stat-box stat-cyan" style="padding: 12px 14px;">
                        <div>
                            <b id="riwayat-stat-santri" style="font-size: 22px;">0</b>
                            <span style="font-size: 12px;">Santri Dibina</span>
                        </div>
                    </div>
                    <div class="stat-box stat-gold" style="padding: 12px 14px;">
                        <div>
                            <b id="riwayat-stat-avg" style="font-size: 22px;">-</b>
                            <span style="font-size: 12px;">Rata-rata Nilai</span>
                        </div>
                    </div>
                </div>

                <!-- Table of Submissions -->
                <div class="table-responsive" style="max-height: 420px; overflow-y: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th scope="col">Waktu</th>
                                <th scope="col">Santri & Kelas</th>
                                <th scope="col">Surah & Ayat</th>
                                <th scope="col">Kategori</th>
                                <th scope="col" style="text-align: center;">Nilai</th>
                                <th scope="col">Catatan</th>
                            </tr>
                        </thead>
                        <tbody id="riwayat-table-body">
                            <!-- Populated dynamically via JS -->
                        </tbody>
                    </table>
                </div>

                <div id="riwayat-empty" class="empty-state" style="display: none; border: none; padding: 24px 0;">
                    <h3>Belum ada riwayat setoran</h3>
                    <p>Ustadz/Ustadzah ini belum mencatatkan penyimakan hafalan siswa.</p>
                </div>
            </div>
        </div>

        <div class="modal-footer-bar">
            <a href="#" id="riwayat-full-page-btn" class="btn-secondary" style="margin-right: auto; text-decoration: none;">
                <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                    <polyline points="15 3 21 3 21 9"/>
                    <line x1="10" y1="14" x2="21" y2="3"/>
                </svg>
                <span>Buka Halaman Penuh</span>
            </a>
            <button type="button" class="btn-primary" id="btn-close-riwayat-bottom">Tutup</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // --- 1. Client-Side Search & Filter Petugas Cards ---
    const searchInput = document.getElementById('search-petugas-input');
    const cards = document.querySelectorAll('.petugas-card');
    const noResultsEl = document.getElementById('no-search-results');
    let currentFilterStatus = 'all';

    function applyFilters() {
        const query = (searchInput ? searchInput.value : '').toLowerCase().trim();
        let visibleCount = 0;

        cards.forEach(card => {
            const nama = card.getAttribute('data-nama') || '';
            const status = card.getAttribute('data-status') || '';

            const matchesQuery = !query || nama.includes(query);
            const matchesStatus = (currentFilterStatus === 'all') || (status === currentFilterStatus);

            if (matchesQuery && matchesStatus) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (noResultsEl) {
            noResultsEl.style.display = (visibleCount === 0 && cards.length > 0) ? 'block' : 'none';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }

    window.filterPetugasStatus = function(status, btn) {
        currentFilterStatus = status;
        document.querySelectorAll('.admin-filter-pill-btn').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');
        applyFilters();
    };

    // --- 2. Edit Petugas Modal ---
    const editPetugasModal = document.getElementById('edit-petugas-modal');
    const closeEditPetugasBtn = document.getElementById('btn-close-edit-petugas');
    const closeEditIconBtn = document.getElementById('btn-close-edit-icon');

    window.openEditPetugasModal = function(data) {
        document.getElementById('edit-petugas-form').action = data.updateUrl;
        document.getElementById('edit-petugas-nama').value = data.nama;

        if (data.isActive !== undefined) {
            document.getElementById('edit-petugas-status-aktif').checked = Boolean(data.isActive);
            document.getElementById('edit-petugas-status-nonaktif').checked = !Boolean(data.isActive);
        } else {
            document.getElementById('edit-petugas-status-aktif').checked = true;
        }

        editPetugasModal.classList.add('active');
        document.getElementById('edit-petugas-nama').focus();
    };

    function closeEditModal() {
        if (editPetugasModal) editPetugasModal.classList.remove('active');
    }

    if (closeEditPetugasBtn) closeEditPetugasBtn.addEventListener('click', closeEditModal);
    if (closeEditIconBtn) closeEditIconBtn.addEventListener('click', closeEditModal);

    // --- 3. Riwayat Penyimakan Modal with Dynamic AJAX Loading ---
    const riwayatModal = document.getElementById('riwayat-petugas-modal');
    const closeRiwayatIcon = document.getElementById('btn-close-riwayat-icon');
    const closeRiwayatBottom = document.getElementById('btn-close-riwayat-bottom');

    window.openRiwayatPetugasModal = function(data) {
        const titleEl = document.getElementById('riwayat-modal-title');
        const subEl = document.getElementById('riwayat-modal-sub');
        const loadingEl = document.getElementById('riwayat-loading');
        const contentEl = document.getElementById('riwayat-content');
        const fullPageBtn = document.getElementById('riwayat-full-page-btn');

        titleEl.textContent = 'Riwayat Penyimakan: ' + data.nama;
        subEl.textContent = 'Memuat data setoran...';
        fullPageBtn.href = data.riwayatUrl;

        loadingEl.style.display = 'block';
        contentEl.style.display = 'none';
        riwayatModal.classList.add('active');

        fetch(data.riwayatUrl, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(result => {
            loadingEl.style.display = 'none';
            contentEl.style.display = 'block';

            if (result.success && result.petugas) {
                document.getElementById('riwayat-stat-total').textContent = result.petugas.total_setoran;
                document.getElementById('riwayat-stat-santri').textContent = result.petugas.total_santri;
                document.getElementById('riwayat-stat-avg').textContent = result.petugas.avg_nilai > 0 ? result.petugas.avg_nilai : '-';
                subEl.textContent = `${result.petugas.total_setoran} setoran tercatat &middot; Status: ${result.petugas.is_active ? 'Aktif' : 'Nonaktif'}`;

                const tbody = document.getElementById('riwayat-table-body');
                const emptyEl = document.getElementById('riwayat-empty');
                tbody.innerHTML = '';

                if (result.data && result.data.length > 0) {
                    emptyEl.style.display = 'none';
                    result.data.forEach(item => {
                        const tr = document.createElement('tr');
                        const catClass = item.kategori === 'Ziyadah' ? 'tag-ziyadah' : 'tag-murojaah';
                        tr.innerHTML = `
                            <td style="white-space: nowrap; font-size: 13px; color: var(--ink-secondary);">${item.waktu_setoran}</td>
                            <td>
                                <strong style="color: var(--ink-primary); font-size: 14px;">${item.nama_siswa}</strong>
                                <div style="font-size: 12px; color: var(--ink-muted);">Kelas ${item.kelas} &bull; ${item.jurusan}</div>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--pri-green);">${item.surah}</div>
                                <div style="font-size: 12px; color: var(--ink-muted);">Ayat ${item.ayat}</div>
                            </td>
                            <td><span class="meta-tag ${catClass}">${item.kategori}</span></td>
                            <td style="text-align: center;">
                                <span style="font-family: 'Fraunces', serif; font-size: 17px; font-weight: 700; color: var(--pri-green);">${item.nilai}</span>
                                <div class="predikat-badge ${item.predikat_class}" style="margin-top: 2px;">${item.predikat_label}</div>
                            </td>
                            <td style="font-size: 12.5px; color: var(--ink-secondary); max-width: 220px;">${item.catatan}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                } else {
                    emptyEl.style.display = 'block';
                }
            }
        })
        .catch(err => {
            loadingEl.style.display = 'none';
            subEl.textContent = 'Gagal memuat riwayat.';
            console.error('Error fetching riwayat:', err);
        });
    };

    function closeRiwayatModal() {
        if (riwayatModal) riwayatModal.classList.remove('active');
    }

    if (closeRiwayatIcon) closeRiwayatIcon.addEventListener('click', closeRiwayatModal);
    if (closeRiwayatBottom) closeRiwayatBottom.addEventListener('click', closeRiwayatModal);

    // Close on backdrop click
    window.addEventListener('click', function(e) {
        if (e.target === editPetugasModal) closeEditModal();
        if (e.target === riwayatModal) closeRiwayatModal();
    });

    // Escape key listener for active modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditModal();
            closeRiwayatModal();
        }
    });
</script>
@endsection
