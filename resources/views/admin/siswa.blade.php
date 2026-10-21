@extends('layouts.app')

@section('title', 'Kelola Data Siswa')

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
        <a href="{{ route('admin.siswa.index') }}" class="tab-btn active">
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

    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; gap: 14px; flex-wrap: wrap;">
        <div>
            <h2>Manajemen Data Siswa</h2>
            <p class="card-desc" style="margin-bottom: 0;">Kelola master siswa Rumah Tahfidz untuk pencatatan dan pelaporan berkala.</p>
        </div>
        <span class="pill-count">
            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
            </svg>
            Total {{ $siswaList->total() }} Siswa Terdaftar
        </span>
    </div>

    <!-- Sub-tab Switcher: Tambah Manual vs Impor CSV -->
    <div class="subtab-switcher" role="tablist" aria-label="Metode Tambah Siswa">
        <button type="button" class="subtab-btn active" id="tab-btn-manual" role="tab" aria-selected="true" aria-controls="panel-manual" onclick="switchSiswaTab('manual')">
            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="16"/>
                <line x1="8" y1="12" x2="16" y2="12"/>
            </svg>
            <span>Tambah Siswa Manual</span>
        </button>
        <button type="button" class="subtab-btn" id="tab-btn-import" role="tab" aria-selected="false" aria-controls="panel-import" onclick="switchSiswaTab('import')">
            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            <span>Impor Massal (CSV)</span>
        </button>
    </div>

    <!-- Panel 1: Tambah Siswa Manual -->
    <div id="panel-manual" role="tabpanel" aria-labelledby="tab-btn-manual" style="margin-bottom: 28px;">
        <div class="ui-card" style="box-shadow: var(--shadow-subtle); border-color: var(--border-line); background-color: #fafcfb;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                <h3 style="margin: 0; font-size: 17px;">Formulir Tambah Siswa Baru</h3>
            </div>
            <p class="card-desc" style="margin-bottom: 16px;">Masukkan informasi siswa ke database sistem tahfidz.</p>

            <form action="{{ route('admin.siswa.store') }}" method="POST">
                @csrf
                <div class="page-grid-2col" style="gap: 16px;">
                    <div>
                        <div class="form-group">
                            <label for="nama-siswa-input" class="form-label">Nama Lengkap Siswa</label>
                            <input type="text" 
                                   id="nama-siswa-input" 
                                   name="nama" 
                                   class="form-input" 
                                   placeholder="mis. Muhammad Rayhan" 
                                   required>
                        </div>

                        <div class="input-row">
                            <div class="form-group">
                                <label for="kelas-siswa-input" class="form-label">Kelas</label>
                                <input type="text" 
                                       id="kelas-siswa-input" 
                                       name="kelas" 
                                       class="form-input" 
                                       placeholder="mis. X-1" 
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="jurusan-siswa-input" class="form-label">Jurusan</label>
                                <select id="jurusan-siswa-input" name="jurusan" class="form-select">
                                    <option value="">Pilih Jurusan</option>
                                    @foreach ($jurusanOptions as $j)
                                        <option value="{{ $j }}">{{ $j }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="form-group">
                            <label for="tahun-siswa-input" class="form-label">Tahun Ajaran</label>
                            <input type="text" 
                                   id="tahun-siswa-input" 
                                   name="tahun_ajaran" 
                                   class="form-input" 
                                   value="{{ $defaultTahun }}" 
                                   required>
                        </div>

                        <div style="margin-top: 30px;">
                            <button type="submit" class="btn-primary" style="width: 100%;">
                                <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                    <polyline points="17 21 17 13 7 13 7 21"/>
                                    <polyline points="7 3 7 8 15 8"/>
                                </svg>
                                <span>Simpan Data Siswa</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Panel 2: Impor Massal Siswa -->
    <div id="panel-import" role="tabpanel" aria-labelledby="tab-btn-import" style="display: none; margin-bottom: 28px;">
        <div class="ui-card" style="box-shadow: var(--shadow-subtle); border-color: var(--border-line); background-color: #fafcfb;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                <h3 style="margin: 0; font-size: 17px;">Impor Massal Data Siswa</h3>
            </div>
            <p class="card-desc" style="margin-bottom: 16px;">Unggah berkas CSV atau tempelkan teks daftar siswa dengan format: nama, kelas, jurusan, tahun ajaran.</p>

            <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="page-grid-2col" style="gap: 16px;">
                    <div>
                        <div class="form-group">
                            <label for="file_csv" class="form-label">Pilih Berkas CSV / TXT</label>
                            <input type="file" 
                                   id="file_csv" 
                                   name="file_csv" 
                                   class="form-input" 
                                   accept=".csv,.txt">
                        </div>

                        <div style="display: flex; gap: 10px; margin-top: 14px;">
                            <a href="{{ route('admin.siswa.template') }}" class="btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                                <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="7 10 12 15 17 10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                                <span>Unduh Templat CSV</span>
                            </a>
                        </div>
                    </div>

                    <div>
                        <div class="form-group">
                            <label for="raw_text" class="form-label">Atau Tempelkan Teks Baris Data</label>
                            <textarea id="raw_text" 
                                      name="raw_text" 
                                      rows="3" 
                                      class="form-textarea" 
                                      placeholder="Ahmad Fauzi, X-1, MIPA, 2026/2027&#10;Siti Aisyah, XI-2, IPS, 2026/2027"></textarea>
                        </div>

                        <button type="submit" class="btn-primary" style="width: 100%; margin-top: 10px;">
                            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            <span>Proses Impor Siswa</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Filter Bar -->
    <form action="{{ route('admin.siswa.index') }}" method="GET" class="filter-toolbar">
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

        <select name="jurusan" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Jurusan</option>
            @foreach ($jurusanOptions as $j)
                <option value="{{ $j }}" {{ $filterJurusan === $j ? 'selected' : '' }}>
                    {{ $j }}
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
                   placeholder="Cari nama siswa..." 
                   value="{{ $search }}">
        </div>
    </form>

    <!-- Tabel Daftar Siswa -->
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th scope="col" style="width: 50px; text-align: center;">No.</th>
                    <th scope="col">Nama Lengkap Siswa</th>
                    <th scope="col">Kelas</th>
                    <th scope="col">Jurusan</th>
                    <th scope="col">Tahun Ajaran</th>
                    <th scope="col" style="text-align: center; width: 150px;">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($siswaList as $idx => $s)
                    <tr>
                        <td style="text-align: center; color: var(--ink-muted); font-size: 13.5px;">
                            {{ $siswaList->firstItem() + $idx }}
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div class="student-avatar" title="{{ $s->nama }}">
                                    {{ mb_substr($s->nama, 0, 1) }}
                                </div>
                                <span style="font-weight: 700; color: var(--ink-primary); font-size: 15px;">
                                    {{ $s->nama }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <span class="meta-tag">Kelas {{ $s->kelas }}</span>
                        </td>
                        <td>
                            @if ($s->jurusan)
                                <span class="meta-tag" style="background-color: #f0fdf4; color: #166534; border-color: #bbf7d0;">
                                    {{ $s->jurusan }}
                                </span>
                            @else
                                <span style="color: var(--ink-muted);">-</span>
                            @endif
                        </td>
                        <td style="font-size: 14px; color: var(--ink-secondary);">
                            {{ $s->tahun_ajaran }}
                        </td>
                        <td style="text-align: center; white-space: nowrap;">
                            <div style="display: inline-flex; gap: 6px;">
                                <button type="button" 
                                        class="btn-secondary btn-sm"
                                        onclick="openEditSiswaModal({
                                            id: '{{ $s->id }}',
                                            nama: '{{ addslashes($s->nama) }}',
                                            kelas: '{{ addslashes($s->kelas) }}',
                                            jurusan: '{{ addslashes($s->jurusan ?? '') }}',
                                            tahun_ajaran: '{{ addslashes($s->tahun_ajaran) }}',
                                            updateUrl: '{{ route('admin.siswa.update', $s) }}'
                                        })"
                                        title="Perbarui Data Siswa">
                                    <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                    <span>Edit</span>
                                </button>
                                <button type="button" 
                                        class="btn-danger btn-sm"
                                        onclick="openConfirmModal({
                                            title: 'Hapus Siswa',
                                            message: 'Apakah Anda yakin ingin menghapus data siswa {{ addslashes($s->nama) }}? Riwayat setoran lampau akan tetap aman terarsip.',
                                            actionUrl: '{{ route('admin.siswa.destroy', $s) }}',
                                            method: 'DELETE',
                                            confirmButtonText: 'Ya, Hapus Siswa'
                                        })"
                                        title="Hapus Data Siswa">
                                    <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    </svg>
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 48px 20px;">
                            <div class="empty-state" style="border: none; background: transparent; padding: 0;">
                                <h3>Belum ada data siswa yang sesuai</h3>
                                <p>Tambahkan siswa baru atau ubah kata kunci filter pencarian di atas.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 22px;">
        {{ $siswaList->links() }}
    </div>
</div>

<!-- Modal Edit Siswa (Accessible & Focus Trap Ready) -->
<div class="modal-overlay" id="edit-modal" role="dialog" aria-modal="true" aria-labelledby="edit-modal-title">
    <div class="modal-container">
        <h2 class="modal-title" id="edit-modal-title">Perbarui Data Siswa</h2>
        
        <form id="edit-siswa-form" method="POST" action="">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="edit-nama" class="form-label">Nama Siswa</label>
                <input type="text" id="edit-nama" name="nama" class="form-input" required>
            </div>

            <div class="input-row">
                <div class="form-group">
                    <label for="edit-kelas" class="form-label">Kelas</label>
                    <input type="text" id="edit-kelas" name="kelas" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="edit-jurusan" class="form-label">Jurusan</label>
                    <select id="edit-jurusan" name="jurusan" class="form-select">
                        <option value="">Pilih Jurusan</option>
                        @foreach ($jurusanOptions as $j)
                            <option value="{{ $j }}">{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="edit-tahun" class="form-label">Tahun Ajaran</label>
                <input type="text" id="edit-tahun" name="tahun_ajaran" class="form-input" required>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 24px;">
                <button type="button" class="btn-secondary" id="btn-close-edit">Batal</button>
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const editModal = document.getElementById('edit-modal');
    const closeEditBtn = document.getElementById('btn-close-edit');

    window.switchSiswaTab = function(mode) {
        const btnManual = document.getElementById('tab-btn-manual');
        const btnImport = document.getElementById('tab-btn-import');
        const panelManual = document.getElementById('panel-manual');
        const panelImport = document.getElementById('panel-import');

        if (mode === 'manual') {
            btnManual.classList.add('active');
            btnManual.setAttribute('aria-selected', 'true');
            btnImport.classList.remove('active');
            btnImport.setAttribute('aria-selected', 'false');
            panelManual.style.display = 'block';
            panelImport.style.display = 'none';
        } else {
            btnImport.classList.add('active');
            btnImport.setAttribute('aria-selected', 'true');
            btnManual.classList.remove('active');
            btnManual.setAttribute('aria-selected', 'false');
            panelImport.style.display = 'block';
            panelManual.style.display = 'none';
        }
    };

    window.openEditSiswaModal = function(data) {
        document.getElementById('edit-siswa-form').action = data.updateUrl;
        document.getElementById('edit-nama').value = data.nama;
        document.getElementById('edit-kelas').value = data.kelas;
        document.getElementById('edit-jurusan').value = data.jurusan;
        document.getElementById('edit-tahun').value = data.tahun_ajaran;

        editModal.classList.add('active');
        document.getElementById('edit-nama').focus();
    };

    if (closeEditBtn && editModal) {
        closeEditBtn.addEventListener('click', () => {
            editModal.classList.remove('active');
        });
    }
</script>
@endsection
