@extends('layouts.app')

@section('title', 'Masuk ke Panel Administrator - Laporan Rumah Tahfidz MAN 1 Probolinggo')

@section('content')
<!-- Hero Sambutan Gerbang Administrator -->
<div class="admin-login-hero no-print">
    <div class="admin-login-badge">
        <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
        </svg>
        <span>Gerbang Masuk Panel Administrator</span>
    </div>
    <h1 class="admin-login-title">Masuk ke Panel Administrator</h1>
    <p class="admin-login-sub">
        Portal autentikasi khusus pengelola Rumah Tahfidz MAN 1 Probolinggo untuk mengelola data santri, ustadz pendamping, rekapitulasi mutaba'ah, dan konfigurasi madrasah.
    </p>
</div>

<div class="login-page-container">
    <div class="ui-card gold-edge admin-login-card">
        <div class="admin-login-card-header">
            <div class="admin-lock-icon">
                <svg class="svg-icon-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>
            <h2>Autentikasi Akun</h2>
            <p class="card-desc">Masukkan alamat email dan kata sandi administrator Anda.</p>
        </div>

        @if ($errors->has('auth'))
            <div class="alert-box alert-danger" style="margin-bottom: 20px;" role="alert">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <span>{{ $errors->first('auth') }}</span>
                </div>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" id="form-login-admin">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email Administrator</label>
                <div class="input-with-icon-wrapper">
                    <svg class="input-leading-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-input with-leading-icon" 
                           placeholder="admin@man1probolinggo.sch.id" 
                           value="{{ old('email') }}"
                           autocomplete="username"
                           required
                           autofocus>
                </div>
                @error('email')
                    <span style="color: #b91c1c; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Kata Sandi Administrator</label>
                <div class="input-password-wrapper">
                    <svg class="input-leading-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-input with-leading-icon" 
                           placeholder="Masukkan kata sandi"
                           autocomplete="current-password"
                           required>
                    <button type="button" 
                            class="btn-toggle-password" 
                            data-target="password" 
                            aria-label="Tampilkan atau sembunyikan kata sandi">
                        <svg class="svg-icon-sm icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span style="color: #b91c1c; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-top: 10px; margin-bottom: 6px;">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--ink-secondary); cursor: pointer; user-select: none;">
                    <input type="checkbox" name="remember" id="remember" value="1" style="accent-color: var(--pri-green); width: 16px; height: 16px; cursor: pointer;">
                    <span>Ingat saya di peramban ini</span>
                </label>
            </div>

            <!-- Petunjuk Kredensial Resmi -->
            <div class="admin-login-info-box">
                <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="16" x2="12" y2="12"/>
                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                </svg>
                <div style="font-size: 12px; color: var(--ink-secondary); line-height: 1.45;">
                    <strong style="color: var(--pri-green);">Informasi Akun:</strong>
                    Gunakan email administrator resmi madrasah. Kata sandi dapat diperbarui di menu Pengaturan.
                </div>
            </div>

            <button type="submit" class="btn-primary btn-full" id="btn-submit-login" style="margin-top: 18px; padding: 12px 20px; font-size: 14.5px;">
                <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                    <polyline points="10 17 15 12 10 7"/>
                    <line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
                <span>Masuk ke Panel Administrator</span>
            </button>
        </form>

        <div class="admin-login-footer-links">
            <a href="{{ route('wali.index') }}" class="admin-return-link">
                <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                <span>Kembali ke Halaman Utama (Pantau Hafalan)</span>
            </a>

            <div style="display: flex; justify-content: center; gap: 8px; font-size: 12.5px; color: var(--ink-muted); margin-top: 10px;">
                <a href="{{ route('petugas.index') }}" style="color: inherit; text-decoration: none;">Halaman Setoran Pendamping</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        document.querySelectorAll('.btn-toggle-password').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                if (!input) return;

                if (input.type === 'password') {
                    input.type = 'text';
                    this.innerHTML = `
                        <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    `;
                    this.setAttribute('aria-label', 'Sembunyikan kata sandi');
                } else {
                    input.type = 'password';
                    this.innerHTML = `
                        <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    `;
                    this.setAttribute('aria-label', 'Tampilkan kata sandi');
                }
            });
        });
    });
</script>
@endsection
