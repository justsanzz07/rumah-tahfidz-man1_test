@extends('layouts.app')

@section('title', 'Riwayat Penyimakan - ' . $petugas->nama)

@section('content')
<div class="ui-card gold-edge">
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

    <!-- Header Breadcrumb & Profile Petugas -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; margin-bottom: 24px; flex-wrap: wrap;">
        <div>
            <div style="margin-bottom: 8px;">
                <a href="{{ route('admin.petugas.index') }}" class="btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; text-decoration: none;">
                    <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Kembali ke Daftar Petugas</span>
                </a>
            </div>
            <div style="display: flex; align-items: center; gap: 14px;">
                <div class="student-avatar" style="width: 52px; height: 52px; font-size: 22px; background: linear-gradient(135deg, var(--gold-light), #f5ecc8); color: var(--gold-dark); border: 2px solid var(--gold-border);">
                    {{ mb_substr($petugas->nama, 0, 1) }}
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <h2 style="margin: 0; font-size: 22px;">{{ $petugas->nama }}</h2>
                        @if ($petugas->is_active)
                            <span class="badge-status-active">
                                <span class="badge-dot"></span>
                                Aktif
                            </span>
                        @else
                            <span class="badge-status-inactive">Nonaktif</span>
                        @endif
                    </div>
                    <p class="card-desc" style="margin-bottom: 0;">Catatan mutaba'ah dan evaluasi hafalan santri yang disimak oleh ustadz ini.</p>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 8px;">
            <button type="button" 
                    class="btn-secondary btn-sm"
                    onclick="openEditPetugasModal({
                        id: '{{ $petugas->id }}',
                        nama: '{{ addslashes($petugas->nama) }}',
                        isActive: {{ $petugas->is_active ? 'true' : 'false' }},
                        updateUrl: '{{ route('admin.petugas.update', $petugas) }}'
                    })">
                <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                <span>Edit Petugas</span>
            </button>
        </div>
    </div>

    <!-- Ringkasan Statistik Ustadz -->
    <div class="stats-row" role="region" aria-label="Statistik Ustadz" style="margin-bottom: 24px;">
        <div class="stat-box">
            <div class="stat-icon-wrap">
                <svg class="svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
            </div>
            <div>
                <b>{{ $totalSetoran }}</b>
                <span>Total Setoran Disimak</span>
            </div>
        </div>
        <div class="stat-box stat-cyan">
            <div class="stat-icon-wrap">
                <svg class="svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <div>
                <b>{{ $totalSantri }}</b>
                <span>Santri Unik Dibina</span>
            </div>
        </div>
        <div class="stat-box stat-gold">
            <div class="stat-icon-wrap">
                <svg class="svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="7"/>
                    <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>
                </svg>
            </div>
            <div>
                <b>{{ $avgNilai > 0 ? $avgNilai : '-' }}</b>
                <span>Rata-rata Nilai Diberikan</span>
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat Setoran -->
    <div style="margin-bottom: 14px;">
        <h3 style="margin: 0; font-size: 17px;">Daftar Setoran Hafalan Siswa</h3>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th scope="col">Waktu Setoran</th>
                    <th scope="col">Siswa & Kelas</th>
                    <th scope="col">Kategori</th>
                    <th scope="col">Surah & Ayat</th>
                    <th scope="col" style="text-align: center;">Nilai & Predikat</th>
                    <th scope="col">Catatan Pembimbing</th>
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
                            <div style="font-weight: 700; color: var(--ink-primary); font-size: 14.5px;">
                                {{ $item->nama_siswa }}
                            </div>
                            <div style="font-size: 12.5px; color: var(--ink-muted);">
                                Kelas {{ $item->kelas }} &bull; {{ $item->jurusan ?? 'Umum' }}
                            </div>
                        </td>
                        <td>
                            <span class="meta-tag {{ $item->kategori === 'Ziyadah' ? 'tag-ziyadah' : 'tag-murojaah' }}">
                                {{ $item->kategori }}
                            </span>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: var(--pri-green);">{{ $item->surah }}</div>
                            <div style="font-size: 12.5px; color: var(--ink-muted);">Ayat {{ $item->ayat }}</div>
                        </td>
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
                        <td style="font-size: 13px; color: var(--ink-secondary); max-width: 280px;">
                            {{ $item->catatan ?: '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 48px 20px;">
                            <div class="empty-state" style="border: none; background: transparent; padding: 0;">
                                <h3>Belum ada riwayat setoran</h3>
                                <p>Petugas ini belum mencatatkan setoran hafalan siswa dalam sistem.</p>
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

<!-- Modal Edit Petugas (Disediakan agar tombol edit di halaman riwayat juga berfungsi) -->
<div class="modal-overlay" id="edit-petugas-modal" role="dialog" aria-modal="true" aria-labelledby="edit-petugas-modal-title">
    <div class="modal-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h2 class="modal-title" id="edit-petugas-modal-title" style="margin: 0;">Edit Data Petugas</h2>
            <button type="button" class="btn-modal-close" id="btn-modal-close-icon" aria-label="Tutup modal">&times;</button>
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
                <div style="display: flex; gap: 16px; margin-top: 6px;">
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
@endsection

@section('scripts')
<script>
    const editPetugasModal = document.getElementById('edit-petugas-modal');
    const closeEditPetugasBtn = document.getElementById('btn-close-edit-petugas');
    const closeIconBtn = document.getElementById('btn-modal-close-icon');

    window.openEditPetugasModal = function(data) {
        document.getElementById('edit-petugas-form').action = data.updateUrl;
        document.getElementById('edit-petugas-nama').value = data.nama;
        
        if (data.isActive !== undefined) {
            document.getElementById('edit-petugas-status-aktif').checked = data.isActive;
            document.getElementById('edit-petugas-status-nonaktif').checked = !data.isActive;
        } else {
            document.getElementById('edit-petugas-status-aktif').checked = true;
        }

        editPetugasModal.classList.add('active');
        document.getElementById('edit-petugas-nama').focus();
    };

    function closeEditModal() {
        if (editPetugasModal) {
            editPetugasModal.classList.remove('active');
        }
    }

    if (closeEditPetugasBtn) {
        closeEditPetugasBtn.addEventListener('click', closeEditModal);
    }
    if (closeIconBtn) {
        closeIconBtn.addEventListener('click', closeEditModal);
    }
    if (editPetugasModal) {
        editPetugasModal.addEventListener('click', function(e) {
            if (e.target === editPetugasModal) {
                closeEditModal();
            }
        });
    }
</script>
@endsection
