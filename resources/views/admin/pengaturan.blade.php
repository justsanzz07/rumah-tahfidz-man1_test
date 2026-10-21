@extends('layouts.app')

@section('title', 'Pengaturan Sistem & Akun Admin')

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
        <a href="{{ route('admin.petugas.index') }}" class="tab-btn">
            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M22 11l-3 3-2-2"/>
            </svg>
            <span>Kelola Petugas</span>
        </a>
        <a href="{{ route('admin.setting.index') }}" class="tab-btn active">
            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
            </svg>
            <span>Pengaturan & Akun</span>
        </a>
    </nav>

    <h2>Pengaturan Sistem & Akun Administrator</h2>
    <p class="card-desc">Ubah logo identitas aplikasi Rumah Tahfidz dan perbarui kata sandi akun administrator.</p>

    <div class="page-grid-2col">
        <!-- Bagian 1: Ganti Logo Aplikasi -->
        <section class="ui-card" style="box-shadow: none; border-color: var(--border-line); background-color: #fafcfb;" aria-labelledby="logo-setting-title">
            <h3 id="logo-setting-title">Logo Resmi Sekolah</h3>
            <p class="card-desc">Ganti logo yang tampil di bagian atas aplikasi dengan foto atau gambar resmi madrasah.</p>

            <!-- Pratinjau Header Nyata -->
            <div style="background: linear-gradient(135deg, var(--pri-dark) 0%, var(--pri-green) 100%); padding: 16px 18px; border-radius: 14px; border-bottom: 3px solid var(--gold-primary); margin-bottom: 20px; box-shadow: 0 4px 12px rgba(3, 60, 48, 0.15);">
                <span style="font-size: 11px; font-weight: 700; color: #fce8a9; letter-spacing: 0.1em; text-transform: uppercase; display: block; margin-bottom: 8px;">
                    Pratinjau Tampilan Header:
                </span>
                <div style="display: flex; align-items: center; gap: 14px;">
                    @if ($currentLogo && file_exists(public_path($currentLogo)))
                        <img src="{{ asset($currentLogo) }}" 
                             alt="Logo Saat Ini" 
                             style="width: 48px; height: 48px; object-fit: cover; border-radius: 12px; border: 2px solid var(--gold-border); background: #ffffff; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                    @else
                        <div class="brand-icon-box" style="width: 48px; height: 48px; font-size: 20px;">
                            <svg class="svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z" fill="rgba(255,255,255,0.08)" stroke="#dfc888"/>
                                <path d="M6 6h10" stroke="#dfc888"/>
                                <path d="M6 10h10" stroke="#dfc888"/>
                                <path d="M6 14h7" stroke="#dfc888"/>
                            </svg>
                        </div>
                    @endif
                    <div>
                        <div style="color: #fce8a9; font-size: 10.5px; font-weight: 700; letter-spacing: 0.12em;">MAN 1 PROBOLINGGO</div>
                        <div style="color: #ffffff; font-family: 'Fraunces', serif; font-size: 17px; font-weight: 700;">Laporan Rumah Tahfidz</div>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.setting.logo') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="logo" class="form-label">Pilih Foto Logo Baru</label>
                    <input type="file" 
                           id="logo" 
                           name="logo" 
                           class="form-input" 
                           accept=".png,.jpg,.jpeg,.webp,.svg" 
                           required>
                    <small style="color: var(--ink-muted); font-size: 12px; display: block; margin-top: 5px;">
                        Mendukung format PNG, JPG, JPEG, WEBP, atau SVG. Ukuran berkas maksimal 2 MB.
                    </small>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 18px; flex-wrap: wrap;">
                    <button type="submit" class="btn-primary" style="flex: 1;">
                        <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/>
                            <line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                        <span>Simpan Logo Baru</span>
                    </button>
                </div>
            </form>

            @if ($currentLogo)
                <form action="{{ route('admin.setting.logo.reset') }}" method="POST" style="margin-top: 12px;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto kustom dan kembali ke logo default?');">
                    @csrf
                    <button type="submit" class="btn-secondary btn-sm" style="width: 100%; justify-content: center;">
                        <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="1 4 1 10 7 10"/>
                            <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
                        </svg>
                        <span>Kembalikan ke Logo Default</span>
                    </button>
                </form>
            @endif
        </section>

        <!-- Bagian 2: Ganti Kata Sandi Administrator -->
        <section class="ui-card" style="box-shadow: none; border-color: var(--border-line); background-color: #fafcfb;" aria-labelledby="password-setting-title">
            <h3 id="password-setting-title">Keamanan & Kata Sandi Admin</h3>
            <p class="card-desc">Perbarui kata sandi untuk melindungi akses panel kontrol administrator.</p>

            <div style="margin-bottom: 18px; padding: 12px 16px; background-color: var(--bg-canvas); border-radius: 12px; border: 1px solid var(--border-line); font-size: 13.5px; display: flex; align-items: center; gap: 10px;">
                <svg class="svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--pri-green);">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                <div>
                    <span style="color: var(--ink-muted); font-size: 12px; display: block;">Akun Administrator Terdaftar:</span>
                    <strong>{{ $adminUser->email ?? 'admin@man1probolinggo.sch.id' }}</strong>
                </div>
            </div>

            <form action="{{ route('admin.setting.password') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="password_lama" class="form-label">Kata Sandi Saat Ini</label>
                    <input type="password" 
                           id="password_lama" 
                           name="password_lama" 
                           class="form-input" 
                           placeholder="Masukkan kata sandi lama saat ini" 
                           required>
                </div>

                <div class="form-group">
                    <label for="password_baru" class="form-label">Kata Sandi Baru</label>
                    <input type="password" 
                           id="password_baru" 
                           name="password_baru" 
                           class="form-input" 
                           placeholder="Minimal 6 karakter" 
                           minlength="6" 
                           required>
                </div>

                <div class="form-group">
                    <label for="password_baru_confirmation" class="form-label">Ulangi Kata Sandi Baru</label>
                    <input type="password" 
                           id="password_baru_confirmation" 
                           name="password_baru_confirmation" 
                           class="form-input" 
                           placeholder="Konfirmasi kata sandi baru" 
                           minlength="6" 
                           required>
                </div>

                <button type="submit" class="btn-primary btn-full">
                    <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <span>Perbarui Kata Sandi</span>
                </button>
            </form>

            <div style="margin-top: 24px; padding-top: 18px; border-top: 1.5px dashed var(--border-line);">
                <h4 style="margin: 0 0 6px; font-size: 14.5px; font-weight: 700; color: var(--ink-primary);">Sesi &amp; Akses Administrator</h4>
                <p class="card-desc" style="margin-bottom: 14px;">Beralih ke halaman pencatatan setoran atau akhiri sesi login admin secara aman.</p>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('petugas.index') }}" class="btn-secondary" style="display: inline-flex; align-items: center; gap: 6px; text-decoration: none;">
                        <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                        </svg>
                        <span>Halaman Pendamping</span>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn-danger" style="display: inline-flex; align-items: center; gap: 6px;">
                            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            <span>Keluar dari Admin</span>
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
