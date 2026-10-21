@extends('layouts.app')

@section('title', 'Kelola Data Petugas')

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

    <h2>Manajemen Data Petugas Penyimak</h2>
    <p class="card-desc">Kelola daftar nama ustadz dan ustadzah yang bertugas menyimak hafalan Al-Qur'an.</p>

    <div style="max-width: 620px; margin-bottom: 30px;">
        <div class="ui-card" style="box-shadow: var(--shadow-subtle); border-color: var(--border-line); background-color: #fafcfb;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                <h3 style="margin: 0; font-size: 17px;">Tambah Petugas Baru</h3>
            </div>
            <p class="card-desc">Nama petugas akan otomatis muncul pada formulir pencatatan setoran.</p>

            <form action="{{ route('admin.petugas.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama-petugas-input" class="form-label">Nama Petugas / Ustadz / Ustadzah</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" 
                               id="nama-petugas-input" 
                               name="nama" 
                               class="form-input" 
                               placeholder="mis. Ustadz Ahmad Dahlan" 
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

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; gap: 12px; flex-wrap: wrap;">
        <h3 style="margin: 0;">Daftar Petugas Aktif</h3>
        <span class="pill-count">
            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
            </svg>
            {{ $totalPetugas }} Petugas Terdaftar
        </span>
    </div>

    <div style="max-width: 760px;">
        @forelse ($petugasList as $p)
            <div class="history-item">
                <div class="student-avatar" style="background: linear-gradient(135deg, var(--gold-light), #f5ecc8); color: var(--gold-dark); border-color: var(--gold-border);">
                    {{ mb_substr($p->nama, 0, 1) }}
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div class="history-name">{{ $p->nama }}</div>
                    <div class="history-sub">
                        Telah menyimak <strong>{{ $p->laporan_setorans_count }}</strong> kali setoran hafalan siswa
                    </div>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button type="button" 
                            class="btn-secondary btn-sm"
                            onclick="openEditPetugasModal({
                                id: '{{ $p->id }}',
                                nama: '{{ addslashes($p->nama) }}',
                                updateUrl: '{{ route('admin.petugas.update', $p) }}'
                            })"
                            title="Perbarui Nama Petugas">
                        <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        <span>Edit</span>
                    </button>
                    <button type="button" 
                            class="btn-danger btn-sm"
                            onclick="openConfirmModal({
                                title: 'Hapus Petugas',
                                message: 'Apakah Anda yakin ingin menghapus petugas {{ addslashes($p->nama) }}? Laporan setoran terdahulu yang pernah disimak akan tetap aman.',
                                actionUrl: '{{ route('admin.petugas.destroy', $p) }}',
                                method: 'DELETE',
                                confirmButtonText: 'Ya, Hapus Petugas'
                            })"
                            title="Hapus Petugas">
                        <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                        <span>Hapus</span>
                    </button>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <h3>Belum ada petugas terdaftar</h3>
                <p>Tambahkan petugas penyimak menggunakan form di atas.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Edit Petugas -->
<div class="modal-overlay" id="edit-petugas-modal" role="dialog" aria-modal="true" aria-labelledby="edit-petugas-modal-title">
    <div class="modal-container">
        <h2 class="modal-title" id="edit-petugas-modal-title">Perbarui Nama Petugas</h2>
        
        <form id="edit-petugas-form" method="POST" action="">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="edit-petugas-nama" class="form-label">Nama Petugas</label>
                <input type="text" id="edit-petugas-nama" name="nama" class="form-input" required>
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

    window.openEditPetugasModal = function(data) {
        document.getElementById('edit-petugas-form').action = data.updateUrl;
        document.getElementById('edit-petugas-nama').value = data.nama;

        editPetugasModal.classList.add('active');
        document.getElementById('edit-petugas-nama').focus();
    };

    if (closeEditPetugasBtn && editPetugasModal) {
        closeEditPetugasBtn.addEventListener('click', () => {
            editPetugasModal.classList.remove('active');
        });
    }
</script>
@endsection
